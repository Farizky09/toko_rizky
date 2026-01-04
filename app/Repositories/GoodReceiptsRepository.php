<?php

namespace App\Repositories;

use App\Interfaces\GoodReceiptsInterfaces;
use App\Models\Batches;
use App\Models\BatchLocations;
use App\Models\GoodReceipt;
use App\Models\GoodReceiptItem;
use App\Models\Purchases;
use App\Models\PurchasesItems;
use Illuminate\Support\Facades\DB;

class GoodReceiptsRepository implements GoodReceiptsInterfaces
{
    private $goodReceipt;

    public function __construct(GoodReceipt $goodReceipt)
    {
        $this->goodReceipt = $goodReceipt;
    }

    public function get()
    {
        return $this->goodReceipt->all();
    }

    public function getById($id)
    {
        return $this->goodReceipt->find($id);
    }

    public function store($data)
    {
        return DB::transaction(function () use ($data) {
            try {
                $this->validateStoreData($data);
                $goodReceipt = $this->createGoodReceipt($data);
                $this->createGoodReceiptItem($goodReceipt->id, $data['items']);
                return $goodReceipt;
            } catch (\Throwable $th) {
                throw $th;
            }
        });
    }

    public function update($id, $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $goodReceipt = $this->goodReceipt->find($id);
            try {
                if (!$goodReceipt) {
                    throw new \Exception('Good Receipt not found.');
                }
                if (in_array($goodReceipt->status, ['process', 'completed', 'cancelled'])) {
                    throw new \Exception('Good Receipt sudah diproses dan tidak dapat diubah.');
                }
                GoodReceiptItem::where('good_receipt_id', $goodReceipt->id)->delete();

                $goodReceipt->update([
                    'receipt_date' => $data['receipt_date'],
                    'supplier_id' => $data['supplier_id'],
                    'branch_id' => $data['branch_id'],
                    'location_id' => $data['location_id'],
                    'received_by' => $data['received_by'],
                    'total_items' => $data['total_items'],
                    'total_quantity_large' => $data['total_quantity_large'],
                    'total_quantity_small' => $data['total_quantity_small'],
                    'notes' => $data['notes'] ?? null,
                ]);

                $this->createGoodReceiptItem($goodReceipt->id, $data['items']);
                return $goodReceipt;
            } catch (\Throwable $th) {
                throw $th;
            }
        });
    }


    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            try {
                $goodReceipt = $this->goodReceipt->find($id);
                if (!$goodReceipt) {
                    throw new \Exception('Good Receipt not found.');
                }
                if (in_array($goodReceipt->status, ['process', 'completed', 'cancelled'])) {
                    throw new \Exception('Good Receipt sudah diproses dan tidak dapat dihapus.');
                }

                GoodReceiptItem::where('good_receipt_id', $goodReceipt->id)->delete();
                $goodReceipt->delete();
            } catch (\Throwable $th) {
                throw $th;
            }
        });
    }

    public function process($id)
    {
        return DB::transaction(function () use ($id) {
            try {
                $goodReceipt = $this->goodReceipt->with('items')->find($id);
                if (!$goodReceipt) {
                    throw new \Exception('Good Receipt not found.');
                }
                if ($goodReceipt->status !== 'draft') {
                    throw new \Exception('Good Receipt harus berstatus Draft untuk diproses.');
                }

                if ($goodReceipt->items->isEmpty()) {
                    throw new \Exception('Good Receipt harus memiliki item untuk diproses.');
                }

                $goodReceipt->status = 'process';
                $goodReceipt->save();
                return $goodReceipt;
            } catch (\Throwable $th) {
                throw $th;
            }
        });
    }

    public function cancel($id)
    {
        return DB::transaction(function () use ($id) {
            try {
                $goodReceipt = $this->goodReceipt->find($id);
                if (!$goodReceipt) {
                    throw new \Exception('Good Receipt not found.');
                }
                if ($goodReceipt->status === 'cancelled') {
                    throw new \Exception('Good Receipt sudah dibatalkan.');
                }
                if ($goodReceipt->status === 'completed') {
                    throw new \Exception('Good Receipt sudah diterima.');
                }

                $goodReceipt->status = 'cancelled';
                $goodReceipt->save();

                return $goodReceipt;
            } catch (\Throwable $th) {
                throw $th;
            }
        });
    }

    public function complete($id)
    {
        return DB::transaction(function () use ($id) {
            try {
                $goodReceipt = $this->goodReceipt->with('items')->find($id);
                if (!$goodReceipt) {
                    throw new \Exception('Good Receipt not found.');
                }
                if ($goodReceipt->status !== 'process') {
                    throw new \Exception('Good Receipt harus berstatus Process untuk diselesaikan.');
                }


                $this->processPurchaseReceive($goodReceipt);


                foreach ($goodReceipt->items as $item) {
                   
                    if (!$item->batch_number) {
                        $item->batch_number = $this->generateBatchNumber();
                        $item->save();
                    }

                    if ($item->qty_received_large > 0 || $item->qty_received_small > 0) {
                        $this->createBatch($item, $goodReceipt->location_id);
                    }
                }

                $goodReceipt->status = 'completed';
                $goodReceipt->save();

                return $goodReceipt;
            } catch (\Throwable $th) {
                throw $th;
            }
        });
    }
    public function datatable()
    {
        $startDate = request('startDate');
        $endDate = request('endDate');
        $status = request('status');
        return DB::table('good_receipts')
            ->leftJoin('suppliers', 'good_receipts.supplier_id', '=', 'suppliers.id')
            ->leftJoin('branches', 'good_receipts.branch_id', '=', 'branches.id')
            ->leftJoin('purchases', 'good_receipts.purchase_id', '=', 'purchases.id')
            ->leftJoin('locations', 'good_receipts.location_id', '=', 'locations.id')
            ->leftJoin('users', 'good_receipts.received_by', '=', 'users.id')
            ->when($status, function ($query) use ($status) {
                $query->where('good_receipts.status', $status);
            })
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                if ($startDate == $endDate) {
                    $query->whereDate('good_receipts.created_at', $startDate);
                } else {
                    $query->whereBetween('good_receipts.created_at', [
                        $startDate . ' 00:00:00',
                        $endDate . ' 23:59:59'
                    ]);
                }
            })
            ->when($startDate && !$endDate, function ($query) use ($startDate) {
                $query->where('good_receipts.created_at', '>=', $startDate . ' 00:00:00');
            })
            ->when($endDate && !$startDate, function ($query) use ($endDate) {
                $query->where('good_receipts.created_at', '<=', $endDate . ' 23:59:59');
            })
            ->select(
                'good_receipts.id',
                'good_receipts.gr_number as gr_number',
                'good_receipts.receipt_date as receipt_date',
                'good_receipts.status',
                'purchases.purchase_number',
                'suppliers.name as supplier_name',
                'branches.name as branch_name',
                'locations.name as location_name',
                'users.name as received_by_name',
                'good_receipts.created_at'
            )
            ->orderBy('good_receipts.created_at', 'desc');
    }
    private function processPurchaseReceive($goodReceipt)
    {
        $purchase = Purchases::with('purchasesItems')->findOrFail($goodReceipt->purchase_id);

        // Validasi status purchase
        if (!in_array($purchase->status, ['draft', 'partial'])) {
            throw new \Exception('Pembelian ini sudah diproses atau dibatalkan.');
        }

        // Update qty_received pada purchase items (kumulatif)
        $this->updatePurchaseItemsReceived($purchase, $goodReceipt->items);

        // Tentukan status purchase
        $purchase->status = $this->determinePurchaseStatus($purchase);
        $purchase->save();

        return $purchase;
    }

    private function updatePurchaseItemsReceived(Purchases $purchase, $items)
    {
        foreach ($items  as $itemData) {
            $itemId = $itemData->purchase_items_id ?? null;
            if (!$itemId) continue;

            $item = $purchase->purchasesItems->firstWhere('id', $itemId);
            if ($item) {
                $deltaLarge = (float) ($itemData->qty_received_large ?? 0);
                $deltaSmall = (float) ($itemData->qty_received_small ?? 0);

                $item->qty_received_large = max(0, ($item->qty_received_large ?? 0) + $deltaLarge);
                $item->qty_received_small = max(0, ($item->qty_received_small ?? 0) + $deltaSmall);
                $item->item_notes = $itemData->note ?? $item->item_notes ?? null;
                $item->save();
            }
        }
    }

    private function determinePurchaseStatus(Purchases $purchase)
    {
        $allReceived = true;
        foreach ($purchase->purchasesItems as $item) {
            $orderedLarge = (float) ($item->qty_large ?? 0);
            $orderedSmall = (float) ($item->qty_small ?? 0);
            $receivedLarge = (float) ($item->qty_received_large ?? 0);
            $receivedSmall = (float) ($item->qty_received_small ?? 0);

            if ($receivedLarge < $orderedLarge || $receivedSmall < $orderedSmall) {
                $allReceived = false;
                break;
            }
        }
        return $allReceived ? 'completed' : 'partial';
    }

    public function generateGoodReceiptNumber()
    {
        $prefix = 'GR';
        $datePart = date('Ymd');
        $lastRecord = $this->goodReceipt->where('gr_number', 'like', $prefix . $datePart . '%')
            ->orderBy('gr_number', 'desc')
            ->first();

        if (!$lastRecord) {
            $newNumber = 1;
        } else {
            $lastNumber = (int)substr($lastRecord->gr_number, -4);
            $newNumber = $lastNumber + 1;
        }

        return $prefix . $datePart . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
    private function generateBatchNumber()
    {
        try {
            $date = now()->format('Ymd');
            $lastBatch = Batches::whereDate('created_at', today())->latest()->first();

            if ($lastBatch) {
                $lastNumber = (int) substr($lastBatch->batch_number, -4);
                $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            } else {
                $newNumber = '0001';
            }

            $batchNumber = "BAT{$date}{$newNumber}";

            return $batchNumber;
        } catch (\Exception $e) {
            throw new \Exception('Gagal generate nomor batch: ' . $e->getMessage());
        }
    }
    private function validateStoreData($data)
    {
        $requiredFields = ['branch_id', 'location_id', 'supplier_id', 'receipt_date', 'purchase_id'];

        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                throw new \InvalidArgumentException("Field {$field} wajib diisi.");
            }
        }

        if (empty($data['items']) || !is_array($data['items'])) {
            throw new \InvalidArgumentException("Minimal satu item harus ditambahkan.");
        }

        // Validate each item
        foreach ($data['items'] as $index => $item) {
            if (empty($item['product_id'])) {
                throw new \InvalidArgumentException("Product pada item ke-" . ($index + 1) . " tidak valid.");
            }

            $receivedLarge = (float) ($item['qty_received_large'] ?? 0);
            $receivedSmall = (float) ($item['qty_received_small'] ?? 0);

            if ($receivedLarge <= 0 && $receivedSmall <= 0) {
                throw new \InvalidArgumentException("Quantity diterima pada item ke-" . ($index + 1) . " harus lebih dari 0.");
            }

            // Pastikan purchase_items_id ada dan cek sisa jatah
            if (empty($item['purchase_items_id'])) {
                throw new \InvalidArgumentException("Purchase items ID pada item ke-" . ($index + 1) . " wajib diisi.");
            }

            $poItem = PurchasesItems::find($item['purchase_items_id']);
            if (!$poItem) {
                throw new \InvalidArgumentException("Purchase item tidak ditemukan untuk item ke-" . ($index + 1) . ".");
            }

            // Hitung sisa jatah
            $sisaLarge = $poItem->qty_large - ($poItem->qty_received_large ?? 0);
            if ($receivedLarge > $sisaLarge) {
                throw new \InvalidArgumentException("Item ke-" . ($index + 1) . " melebihi sisa pesanan PO untuk qty_large (Sisa: $sisaLarge).");
            }

            $sisaSmall = $poItem->qty_small - ($poItem->qty_received_small ?? 0);
            if ($receivedSmall > $sisaSmall) {
                throw new \InvalidArgumentException("Item ke-" . ($index + 1) . " melebihi sisa pesanan PO untuk qty_small (Sisa: $sisaSmall).");
            }
        }
    }
    private function createGoodReceipt($data)
    {
        $gr_number = $this->generateGoodReceiptNumber();

        return $this->goodReceipt->create([
            'gr_number' => $gr_number,
            'purchase_id' => $data['purchase_id'],
            'receipt_date' => $data['receipt_date'],
            'supplier_id' => $data['supplier_id'],
            'branch_id' => $data['branch_id'],
            'location_id' => $data['location_id'],
            'received_by' => $data['received_by'],
            'status' => 'draft',
            'total_items' => $data['total_items'],
            'total_quantity_large' => $data['total_quantity_large'],
            'total_quantity_small' => $data['total_quantity_small'],
            'notes' => $data['notes'] ?? null,
        ]);
    }

    private function createGoodReceiptItem($goodReceiptId, $itemData)
    {
        foreach ($itemData as $item) {


            GoodReceiptItem::create([
                'good_receipt_id' => $goodReceiptId,
                'product_id' => $item['product_id'],
                'batch_number' => null,
                'purchase_items_id' => $item['purchase_items_id'] ?? null,
                'qty_received_large' => $item['qty_received_large'],
                'qty_received_small' => $item['qty_received_small'],
                'qty_rejected_large' => $item['qty_rejected_large'] ?? 0,
                'qty_rejected_small' => $item['qty_rejected_small'] ?? 0,
                'reject_reason' => $item['reject_reason'] ?? null,
                'expiry_date' => $item['expiry_date'] ?? null,
                'note' => $item['note'] ?? null,
            ]);
        }
    }


    private function createBatch($receipItems,  $locationId)
    {
        $purchaseItem = $receipItems->purchase_items_id ? PurchasesItems::find($receipItems->purchase_items_id) : null;

        $netLarge = max(0, $receipItems->qty_received_large - $receipItems->qty_rejected_large);
        $netSmall = max(0, $receipItems->qty_received_small - $receipItems->qty_rejected_small);

        if ($netLarge == 0 && $netSmall == 0) {
            return; // Tidak ada stok bersih, skip create batch
        }

        $batch = Batches::create([
            'product_id' => $receipItems->product_id,
            'batch_number' => $receipItems->batch_number,
            'purchase_price_large' => $purchaseItem ? ($purchaseItem->purchase_price_large ?? 0) : 0,
            'purchase_price_small' => $purchaseItem ? ($purchaseItem->purchase_price_small ?? 0) : 0,
            'quantity_large' => $netLarge,
            'quantity_small' => $netSmall,
            'selling_price_large' => $purchaseItem ? ($purchaseItem->selling_price_large ?? 0) : 0,
            'selling_price_small' => $purchaseItem ? ($purchaseItem->selling_price_small ?? 0) : 0,
            'expiry_date' => $receipItems->expiry_date,
            'status' => 'active',
        ]);

        BatchLocations::create([
            'batch_id' => $batch->id,
            'location_id' => $locationId,
            'quantity_large' => $netLarge,
            'quantity_small' => $netSmall,
        ]);
    }
}
