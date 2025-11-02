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

        if ($purchase) {
            $purchase->purchasesItems = DB::table('purchase_items')
                ->select(
                    'purchase_items.*',
                    'products.name as product_name',
                    'products.code as product_code'
                )
                ->leftJoin('products', 'purchase_items.product_id', '=', 'products.id')
                ->where('purchase_items.purchase_id', $id)
                ->get();
        }

        return $purchase;
    }


    public function store($data)
    {
        return DB::transaction(function () use ($data) {
            try {


                $purchaseNumber = $this->generatePurchaseNumber();

                if (!isset($data['branch_id']) || !isset($data['location_id']) || !isset($data['supplier_id'])) {
                    throw new \Exception('Required fields are missing');
                }

                $totalItems = count($data['items']);
                $totalQtyLarge = collect($data['items'])->sum(function ($item) {
                    return (int) ($item['qty_large'] ?? 0);
                });
                $totalQtySmall = collect($data['items'])->sum(function ($item) {
                    return (int) ($item['qty_small'] ?? 0);
                });

                $subtotal = $this->calculateSubtotal($data['items']);
                $tax = (float) ($data['tax'] ?? 0);
                $discount = (float) ($data['discount'] ?? 0);
                $totalAmount = $this->calculateTotalAmount($data['items'], $tax, $discount);

                // Create purchase
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
                    'status' => $data['status'],
                    'notes' => $data['notes'] ?? null,
                ]);

                $this->savePurchaseItems($purchase, $data['items']);

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

            $purchase->update([
                'branch_id' => $data['branch_id'],
                'location_id' => $data['location_id'],
                'supplier_id' => $data['supplier_id'],
                'purchase_date' => $data['purchase_date'],
                'total_items' => count($data['items']),
                'total_quantity_large' => collect($data['items'])->sum('qty_large'),
                'total_quantity_small' => collect($data['items'])->sum('qty_small'),
                'subtotal' => $this->calculateSubtotal($data['items']),
                'tax' => $data['tax'] ?? 0,
                'discount' => $data['discount'] ?? 0,
                'total_amount' => $this->calculateTotalAmount($data['items'], $data['tax'], $data['discount']),
                'status' => $data['status'],
                'notes' => $data['notes'],
            ]);

            $purchase->purchasesItems()->delete();
            $this->savePurchaseItems($purchase->id, $data['items']);

            return $purchase;
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $purchases = $this->purchases->findOrFail($id);
            if ($purchases) {

                $purchases->purchasessItems()->delete();
                $purchases->delete();
                return $purchases;
            }
            throw new Exception('Purchases not found');
        });
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
            throw new \Exception('Failed to generate purchase number: ' . $e->getMessage());
        }
    }
    private function generateBatchNumber()
    {
        try {
            $date = now()->format('Ymd');
            $lastBatch = Batches::whereDate('created_at', today())->latest()->first();

            if ($lastBatch) {
                // Ambil angka dari batch_number terakhir
                $lastNumber = preg_replace('/[^0-9]/', '', $lastBatch->batch_number);
                $lastFourDigits = substr($lastNumber, -4);
                $newNumber = str_pad((int)$lastFourDigits + 1, 4, '0', STR_PAD_LEFT);
            } else {
                $newNumber = '0001';
            }

            $batchNumber = "BAT{$date}{$newNumber}";

            return $batchNumber;
        } catch (\Exception $e) {
            throw new \Exception('Failed to generate batch number: ' . $e->getMessage());
        }
    }

    private function savePurchaseItems($purchase, $items)
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

                $purchaseItem = PurchasesItems::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['product_id'],
                    'selling_price_small' => $sellingPriceSmall,
                    'selling_price_large' => $sellingPriceLarge,
                    'qty_large' => $qtyLarge,
                    'qty_small' => $qtySmall,
                    'purchase_price_small' => $purchasePriceSmall,
                    'purchase_price_large' => $purchasePriceLarge,
                    'subtotal' => $subtotal,
                ]);

                $batchNumber = $this->generateBatchNumber();

                $batch = Batches::create([
                    'product_id' => $purchaseItem->product_id,
                    'batch_number' => $batchNumber,
                    'purchase_price_large' => $purchaseItem->purchase_price_large,
                    'purchase_price_small' => $purchaseItem->purchase_price_small,
                    'quantity_large' => $purchaseItem->qty_large,
                    'quantity_small' => $purchaseItem->qty_small,
                    'selling_price_large' => $purchaseItem->selling_price_large,
                    'selling_price_small' => $purchaseItem->selling_price_small,
                    'expiry_date' => $expiryDate,
                    'status' => 'active',
                ]);
                // $lcoations = $purchaseItem->purchases->location_id;
                BatchLocations::create([
                    'batch_id' => $batch->id,
                    'location_id' => $purchase->location_id,
                    'quantity_large' => $batch->quantity_large,
                    'quantity_small' => $batch->quantity_small,
                ]);
            } catch (\Exception $e) {
                throw new \Exception("Failed to save item {$index}: " . $e->getMessage());
            }
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
