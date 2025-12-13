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

                foreach ($data['items'] as $itemData) {
                    PurchasesItems::create([
                        'purchase_id' => $purchase->id,
                        'product_id' => $itemData['product_id'],
                        'qty_large' => $itemData['qty_large'] ?? 0,
                        'qty_small' => $itemData['qty_small'] ?? 0,
                        'selling_price_large' => $itemData['selling_price_large'] ?? 0,
                        'selling_price_small' => $itemData['selling_price_small'] ?? 0,
                        'qty_received_large' => $itemData['qty_received_large'] ?? 0,
                        'qty_received_small' => $itemData['qty_received_small'] ?? 0,
                        'purchase_price_large' => $itemData['purchase_price_large'] ?? 0,
                        'purchase_price_small' => $itemData['purchase_price_small'] ?? 0,
                        'subtotal' => ((int)($itemData['qty_large'] ?? 0) * (float)($itemData['purchase_price_large'] ?? 0)) +
                            ((int)($itemData['qty_small'] ?? 0) * (float)($itemData['purchase_price_small'] ?? 0)),
                        'item_notes' => $itemData['item_notes'] ?? null,
                    ]);
                }

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
            $purchase->branch_id = $data['branch_id'] ?? $purchase->branch_id;
            $purchase->location_id = $data['location_id'] ?? $purchase->location_id;
            $purchase->supplier_id = $data['supplier_id'] ?? $purchase->supplier_id;
            $purchase->purchase_date = $data['purchase_date'] ?? $purchase->purchase_date;
            $purchase->notes = $data['notes'] ?? $purchase->notes;

            $totalItems = count($data['items']);
            $totalQtyLarge = collect($data['items'])->sum(fn($item) => (int) ($item['qty_large'] ?? 0));
            $totalQtySmall = collect($data['items'])->sum(fn($item) => (int) ($item['qty_small'] ?? 0));
            $subtotal = $this->calculateSubtotal($data['items']);
            $tax = (float) ($data['tax'] ?? 0);
            $discount = (float) ($data['discount'] ?? 0);
            $totalAmount = $this->calculateTotalAmount($data['items'], $tax, $discount);

            $purchase->total_items = $totalItems;
            $purchase->total_quantity_large = $totalQtyLarge;
            $purchase->total_quantity_small = $totalQtySmall;
            $purchase->subtotal = $subtotal;
            $purchase->tax = $tax;
            $purchase->discount = $discount;
            $purchase->total_amount = $totalAmount;

            $purchase->save();

            // Hapus item lama
            PurchasesItems::where('purchase_id', $purchase->id)->delete();

            // Tambahkan item baru
            foreach ($data['items'] as $itemData) {
                PurchasesItems::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $itemData['product_id'],
                    'qty_large' => $itemData['qty_large'] ?? 0,
                    'qty_small' => $itemData['qty_small'] ?? 0,
                    'selling_price_large' => $itemData['selling_price_large'] ?? 0,
                    'selling_price_small' => $itemData['selling_price_small'] ?? 0,
                    'qty_received_large' => $itemData['qty_received_large'] ?? 0,
                    'qty_received_small' => $itemData['qty_received_small'] ?? 0,
                    'purchase_price_large' => $itemData['purchase_price_large'] ?? 0,
                    'purchase_price_small' => $itemData['purchase_price_small'] ?? 0,
                    'subtotal' => ((int)($itemData['qty_large'] ?? 0) * (float)($itemData['purchase_price_large'] ?? 0)) +
                        ((int)($itemData['qty_small'] ?? 0) * (float)($itemData['purchase_price_small'] ?? 0)),
                    'item_notes' => $itemData['item_notes'] ?? null,
                ]);
            }
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
