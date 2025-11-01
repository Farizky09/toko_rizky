<?php

namespace App\Repositories;

use App\Http\Requests\PurchasesRequest;
use App\Interfaces\PurchasesInterfaces;
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


    public function store(PurchasesRequest $request)
    {
        return DB::transaction(function () use ($request) {

            $purchasesNumber = $this->purchases->generatePurchaseNumber();

            $purchase = Purchases::create([
                'purchase_number' => $purchasesNumber,
                'branch_id' => $request->branch_id,
                'location_id' => $request->location_id,
                'supplier_id' => $request->supplier_id,
                'user_id' => Auth::user()->id,
                'purchase_date' => $request->purchase_date,
                'total_items' => count($request->items),
                'total_quantity_large' => collect($request->items)->sum('qty_large'),
                'total_quantity_small' => collect($request->items)->sum('qty_small'),
                'subtotal' => $this->calculateSubtotal($request->items),
                'tax' => $request->tax ?? 0,
                'discount' => $request->discount ?? 0,
                'total_amount' => $this->calculateTotalAmount($request->items, $request->tax, $request->discount),
                'status' => $request->status,
                'notes' => $request->notes,
            ]);
            $this->savePurchaseItems($purchase->id, $request->items);

            return $purchase;
        });
    }

    public function update(PurchasesRequest $request, $id)
    {
        return DB::transaction(function () use ($request, $id) {
            $purchase = Purchases::findOrFail($id);

            $purchase->update([
                'branch_id' => $request->branch_id,
                'location_id' => $request->location_id,
                'supplier_id' => $request->supplier_id,
                'purchase_date' => $request->purchase_date,
                'total_items' => count($request->items),
                'total_quantity_large' => collect($request->items)->sum('qty_large'),
                'total_quantity_small' => collect($request->items)->sum('qty_small'),
                'subtotal' => $this->calculateSubtotal($request->items),
                'tax' => $request->tax ?? 0,
                'discount' => $request->discount ?? 0,
                'total_amount' => $this->calculateTotalAmount($request->items, $request->tax, $request->discount),
                'status' => $request->status,
                'notes' => $request->notes,
            ]);

            $purchase->purchasesItems()->delete();
            $this->savePurchaseItems($purchase->id, $request->items);

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
        $date = now()->format('Ymd');
        $lastPurchase = Purchases::whereDate('created_at', today())->latest()->first();

        if ($lastPurchase) {
            $lastNumber = (int) substr($lastPurchase->purchase_number, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "PUR{$date}{$newNumber}";
    }

    private function savePurchaseItems($purchaseId, $items)
    {
        foreach ($items as $item) {
            $subtotal = ($item['qty_large'] * $item['purchase_price_large']) +
                ($item['qty_small'] * $item['purchase_price_small']);

            PurchasesItems::create([
                'purchase_id' => $purchaseId,
                'product_id' => $item['product_id'],
                'selling_price_small' => $item['selling_price_small'],
                'selling_price_large' => $item['selling_price_large'],
                'qty_large' => $item['qty_large'],
                'qty_small' => $item['qty_small'],
                'purchase_price_small' => $item['purchase_price_small'],
                'purchase_price_large' => $item['purchase_price_large'],
                'subtotal' => $subtotal,
            ]);
        }
    }
    private function calculateSubtotal($items)
    {
        return collect($items)->reduce(function ($carry, $item) {
            return $carry + ($item['qty_large'] * $item['purchase_price_large']) +
                ($item['qty_small'] * $item['purchase_price_small']);
        }, 0);
    }

    private function calculateTotalAmount($items, $tax, $discount)
    {
        $subtotal = $this->calculateSubtotal($items);
        return ($subtotal + $tax) - $discount;
    }
}
