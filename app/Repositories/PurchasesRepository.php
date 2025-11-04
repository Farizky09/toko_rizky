<?php

namespace App\Repositories;


use App\Interfaces\PurchasesInterfaces;
use App\Models\Batches;
use App\Models\BatchLocations;
use App\Models\Purchases;
use App\Models\PurchasesItems;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchasesRepository implements PurchasesInterfaces
{
    private $purchases;

    public function __construct(Purchases $purchases)
    {
        $this->purchases = $purchases;
    }

    public function get()
    {
        return DB::table('purchases')
            ->select(
                'purchases.*',
                'branches.name as branch_name',
                'locations.name as location_name',
                'suppliers.name as supplier_name',
                'users.name as user_name'
            )
            ->leftJoin('branches', 'purchases.branch_id', '=', 'branches.id')
            ->leftJoin('locations', 'purchases.location_id', '=', 'locations.id')
            ->leftJoin('suppliers', 'purchases.supplier_id', '=', 'suppliers.id')
            ->leftJoin('users', 'purchases.user_id', '=', 'users.id')
            ->orderBy('purchases.created_at', 'desc')
            ->get();
    }

    public function getById($id)
    {
        $purchase = DB::table('purchases')
            ->select(
                'purchases.*',
                'branches.name as branch_name',
                'locations.name as location_name',
                'suppliers.name as supplier_name',
                'users.name as user_name'
            )
            ->leftJoin('branches', 'purchases.branch_id', '=', 'branches.id')
            ->leftJoin('locations', 'purchases.location_id', '=', 'locations.id')
            ->leftJoin('suppliers', 'purchases.supplier_id', '=', 'suppliers.id')
            ->leftJoin('users', 'purchases.user_id', '=', 'users.id')
            ->where('purchases.id', $id)
            ->first();
        if (!$purchase) {
            abort(404, 'Purchase tidak ditemukan');
        }

        $purchase->purchasesItems = DB::table('purchase_items')
            ->select(
                'purchase_items.*',
                'products.name as product_name',
                'products.code as product_code'
            )
            ->leftJoin('products', 'purchase_items.product_id', '=', 'products.id')
            ->where('purchase_items.purchase_id', $id)
            ->get();


        return $purchase;
    }


    public function datatable()
    {
        return DB::table('purchases')
            ->select(
                'purchases.*',
                'branches.name as branch_name',
                'locations.name as location_name',
                'suppliers.name as supplier_name',
                'users.name as user_name'
            )
            ->leftJoin('branches', 'purchases.branch_id', '=', 'branches.id')
            ->leftJoin('locations', 'purchases.location_id', '=', 'locations.id')
            ->leftJoin('suppliers', 'purchases.supplier_id', '=', 'suppliers.id')
            ->leftJoin('users', 'purchases.user_id', '=', 'users.id')
            ->orderBy('purchases.created_at', 'desc');
    }
    public function store($data)
    {
        return DB::transaction(function () use ($data) {
            try {
                if (!isset($data['branch_id']) || !isset($data['location_id']) || !isset($data['supplier_id'])) {
                    throw new \Exception('Field Cabang, Lokasi, dan Supplier wajib diisi.');
                }

                $totalItems = count($data['items']);
                $totalQtyLarge = collect($data['items'])->sum(fn($item) => (int) ($item['qty_large'] ?? 0));
                $totalQtySmall = collect($data['items'])->sum(fn($item) => (int) ($item['qty_small'] ?? 0));
                $subtotal = $this->calculateSubtotal($data['items']);
                $tax = (float) ($data['tax'] ?? 0);
                $discount = (float) ($data['discount'] ?? 0);
                $totalAmount = $this->calculateTotalAmount($data['items'], $tax, $discount);
                $purchaseNumber = $this->generatePurchaseNumber();

                $purchase = Purchases::create([
                    'purchase_number' => $purchaseNumber,
                    'branch_id' => $data['branch_id'],
                    'location_id' => $data['location_id'],
                    'supplier_id' => $data['supplier_id'],
                    'user_id' => auth()->user()->id ?? null,
                    'purchase_date' => $data['purchase_date'],
                    'total_items' => $totalItems,
                    'total_quantity_large' => $totalQtyLarge,
                    'total_quantity_small' => $totalQtySmall,
                    'subtotal' => $subtotal,
                    'tax' => $tax,
                    'discount' => $discount,
                    'total_amount' => $totalAmount,
                    'status' => 'draft',
                    'notes' => $data['notes'] ?? null,
                ]);

                $this->savePurchaseItemsOnly($purchase, $data['items']);

                return $purchase;
            } catch (\Exception $e) {
                throw $e;
            }
        });
    }

    public function update($data, $id)
    {
        return DB::transaction(function () use ($data, $id) {
            $purchase = Purchases::findOrFail($id);

            if ($purchase->status !== 'draft') {
                throw new \Exception('Hanya pembelian dengan status "draft" yang bisa di-update.');
            }

            $totalItems = count($data['items']);
            $totalQtyLarge = collect($data['items'])->sum(fn($item) => (int) ($item['qty_large'] ?? 0));
            $totalQtySmall = collect($data['items'])->sum(fn($item) => (int) ($item['qty_small'] ?? 0));
            $subtotal = $this->calculateSubtotal($data['items']);
            $tax = (float) ($data['tax'] ?? 0);
            $discount = (float) ($data['discount'] ?? 0);
            $totalAmount = $this->calculateTotalAmount($data['items'], $tax, $discount);

            $purchase->update([
                'branch_id' => $data['branch_id'],
                'location_id' => $data['location_id'],
                'supplier_id' => $data['supplier_id'],
                'purchase_date' => $data['purchase_date'],
                'total_items' => $totalItems,
                'total_quantity_large' => $totalQtyLarge,
                'total_quantity_small' => $totalQtySmall,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'discount' => $discount,
                'total_amount' => $totalAmount,
                'notes' => $data['notes'] ?? null,
            ]);

            $purchase->purchasesItems()->delete();
            $this->savePurchaseItemsOnly($purchase, $data['items']);

            return $purchase;
        });
    }

    public function receivePurchase($id, $receivedData)
    {
        return DB::transaction(function () use ($id, $receivedData) {
            $purchase = Purchases::with('purchasesItems')->findOrFail($id);

            if ($purchase->status !== 'draft') {
                throw new \Exception('Pembelian ini sudah diproses atau dibatalkan.');
            }

            foreach ($receivedData['items'] as $itemId => $itemData) {
                $itemId = (int) $itemId;
                $item = $purchase->purchasesItems->firstWhere('id', $itemId);

                if ($item) {
                    $item->update([
                        'qty_received_large' => (float)($itemData['qty_received_large'] ?? 0),
                        'qty_received_small' => (float)($itemData['qty_received_small'] ?? 0),
                        'item_notes' => $itemData['item_notes'] ?? null,
                    ]);
                }
            }

            $this->processStockIn($purchase);


            $purchase->status = 'completed';
            $purchase->save();

            return $purchase;
        });
    }


    public function cancel($id)
    {
        return DB::transaction(function () use ($id) {
            $purchase = $this->purchases->findOrFail($id);

            if ($purchase->status !== 'draft') {
                throw new Exception('Hanya pembelian "draft" yang bisa dibatalkan.');
            }

            $purchase->status = 'cancelled';
            $purchase->save();


            return $purchase;
        });
    }


    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $purchase = $this->purchases->findOrFail($id);

            if ($purchase->status !== 'cancelled') {
                throw new Exception('Hanya pembelian "Cancelled" yang boleh dihapus permanen.');
            }

            $purchase->purchasesItems()->delete();
            $purchase->delete();

            return $purchase;
        });
    }


    private function savePurchaseItemsOnly(Purchases $purchase, array $items)
    {
        foreach ($items as $index => $item) {
            try {
                if (!isset($item['product_id'])) {
                    throw new \Exception("Product ID is missing for item {$index}");
                }

                $qtyLarge = (float) ($item['qty_large'] ?? 0);
                $qtySmall = (float) ($item['qty_small'] ?? 0);
                $purchasePriceLarge = (float) ($item['purchase_price_large'] ?? 0);
                $purchasePriceSmall = (float) ($item['purchase_price_small'] ?? 0);
                $sellingPriceLarge = (float) ($item['selling_price_large'] ?? 0);
                $sellingPriceSmall = (float) ($item['selling_price_small'] ?? 0);
                $expiryDate = $item['expiry_date'] ?? null;

                $subtotal = ($qtyLarge * $purchasePriceLarge) + ($qtySmall * $purchasePriceSmall);

                PurchasesItems::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['product_id'],
                    'selling_price_small' => $sellingPriceSmall,
                    'selling_price_large' => $sellingPriceLarge,
                    'qty_large' => $qtyLarge,
                    'qty_small' => $qtySmall,
                    'purchase_price_small' => $purchasePriceSmall,
                    'purchase_price_large' => $purchasePriceLarge,
                    'expiry_date' => $expiryDate,
                    'subtotal' => $subtotal,
                ]);
            } catch (\Exception $e) {
                throw new \Exception("Gagal menyimpan item {$index}: " . $e->getMessage());
            }
        }
    }

    private function processStockIn(Purchases $purchase)
    {
        $purchase->load('purchasesItems');

        foreach ($purchase->purchasesItems as $item) {

            if ($item->qty_received_large > 0 || $item->qty_received_small > 0) {
                $batchNumber = $this->generateBatchNumber();

                $batch = Batches::create([
                    'product_id' => $item->product_id,
                    'batch_number' => $batchNumber,
                    'purchase_price_large' => $item->purchase_price_large,
                    'purchase_price_small' => $item->purchase_price_small,
                    'quantity_large' => $item->qty_received_large,
                    'quantity_small' => $item->qty_received_small,
                    'selling_price_large' => $item->selling_price_large,
                    'selling_price_small' => $item->selling_price_small,
                    'expiry_date' => $item->expiry_date,
                    'status' => 'active',
                ]);

                BatchLocations::create([
                    'batch_id' => $batch->id,
                    'location_id' => $purchase->location_id,
                    'quantity_large' => $item->qty_received_large,
                    'quantity_small' => $item->qty_received_small,
                ]);
            }
        }
    }


    public function generatePurchaseNumber()
    {
        try {
            $date = now()->format('Ymd');
            $lastPurchase = Purchases::whereDate('created_at', today())->latest()->first();

            if ($lastPurchase) {
                $lastNumber = preg_replace('/[^0-9]/', '', $lastPurchase->purchase_number);
                $lastFourDigits = substr($lastNumber, -4);
                $newNumber = str_pad((int)$lastFourDigits + 1, 4, '0', STR_PAD_LEFT);
            } else {
                $newNumber = '0001';
            }

            $purchaseNumber = "PUR{$date}{$newNumber}";

            return $purchaseNumber;
        } catch (\Exception $e) {
            throw new \Exception('Gagal generate nomor purchase: ' . $e->getMessage());
        }
    }

    private function generateBatchNumber()
    {
        try {
            $date = now()->format('Ymd');
            $lastBatch = Batches::whereDate('created_at', today())->latest()->first();

            if ($lastBatch) {
                $lastNumber = preg_replace('/[^0-9]/', '', $lastBatch->batch_number);
                $lastFourDigits = substr($lastNumber, -4);
                $newNumber = str_pad((int)$lastFourDigits + 1, 4, '0', STR_PAD_LEFT);
            } else {
                $newNumber = '0001';
            }

            $batchNumber = "BAT{$date}{$newNumber}";

            return $batchNumber;
        } catch (\Exception $e) {
            throw new \Exception('Gagal generate nomor batch: ' . $e->getMessage());
        }
    }

    private function calculateSubtotal($items)
    {
        return collect($items)->reduce(function ($carry, $item) {
            $qtyLarge = (int) ($item['qty_large'] ?? 0);
            $qtySmall = (int) ($item['qty_small'] ?? 0);
            $priceLarge = (float) ($item['purchase_price_large'] ?? 0);
            $priceSmall = (float) ($item['purchase_price_small'] ?? 0);
            $itemSubtotal = ($qtyLarge * $priceLarge) + ($qtySmall * $priceSmall);
            return $carry + $itemSubtotal;
        }, 0);
    }

    private function calculateTotalAmount($items, $tax, $discount)
    {
        $subtotal = $this->calculateSubtotal($items);
        $tax = (float) ($tax ?? 0);
        $discount = (float) ($discount ?? 0);
        $total = ($subtotal + $tax) - $discount;
        return $total;
    }
}
