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

                // Jika ada purchase_id, proses receive purchase terlebih dahulu
                if (isset($data['purchase_id']) && !empty($data['purchase_id'])) {
                    $this->processPurchaseReceive($data['purchase_id'], $data);
                }

                $goodReceipt = $this->createGoodReceipt($data);
                $this->createGoodReceiptItem($goodReceipt->id, $data['items'], $data['location_id']);
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
                if ($goodReceipt->status == 'completed') {
                    throw new \Exception('Good Receipt sudah diterima dan tidak dapat diubah.');
                }
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
            } catch (\Throwable $th) {
                //throw $th;
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
                if ($goodReceipt->status == 'completed') {
                    throw new \Exception('Good Receipt sudah diterima dan tidak dapat dihapus.');
                }
                // Hapus item terkait
                GoodReceiptItem::where('good_receipt_id', $goodReceipt->id)->delete();
                // Hapus good receipt
                $goodReceipt->delete();
            } catch (\Throwable $th) {
                throw $th;
            }
        });
    }

    private function processPurchaseReceive($purchaseId, &$data)
    {
        $purchase = Purchases::with('purchasesItems')->findOrFail($purchaseId);

        // Validasi status purchase
        if (!in_array($purchase->status, ['draft', 'partial'])) {
            throw new \Exception('Pembelian ini sudah diproses atau dibatalkan.');
        }

        // Update qty_received pada purchase items (kumulatif)
        $this->updatePurchaseItemsReceived($purchase, $data['items']);

        // Tentukan status purchase
        $purchase->status = $this->determinePurchaseStatus($purchase);
        $purchase->save();
    }

    private function updatePurchaseItemsReceived(Purchases $purchase, array $receivedItems)
    {
        foreach ($receivedItems as $itemData) {
            $itemId = $itemData['purchase_items_id'] ?? null;
            if (!$itemId) continue;

            $item = $purchase->purchasesItems->firstWhere('id', $itemId);
            if ($item) {
                $deltaLarge = (float) ($itemData['qty_received_large'] ?? 0);
                $deltaSmall = (float) ($itemData['qty_received_small'] ?? 0);

                $item->qty_received_large = max(0, ($item->qty_received_large ?? 0) + $deltaLarge);
                $item->qty_received_small = max(0, ($item->qty_received_small ?? 0) + $deltaSmall);
                $item->item_notes = $itemData['note'] ?? $item->item_notes ?? null;
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

    private function generateGoodReceiptNumber()
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
    private function validateStoreData($data)
    {
        // Masukkan 'purchase_id' langsung ke array required
        $requiredFields = ['branch_id', 'location_id', 'supplier_id', 'receipt_date', 'purchase_id'];

        // Jika ada purchase_id, itu wajib
        if (isset($data['purchase_id']) && !empty($data['purchase_id'])) {
            $requiredFields[] = 'purchase_id';
        }

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

            // Jika ada purchase_id, pastikan purchase_items_id ada
            if (isset($data['purchase_id']) && empty($item['purchase_items_id'])) {
                throw new \InvalidArgumentException("Purchase items ID pada item ke-" . ($index + 1) . " wajib diisi untuk receive purchase.");
            }
            if (isset($data['purchase_id'])) {
                $poItem = PurchasesItems::find($item['purchase_items_id']);

                // Hitung sisa jatah
                $sisaJatah = $poItem->qty_large - $poItem->qty_received_large;

                // Cek inputan sekarang
                if ($item['qty_received_large'] > $sisaJatah) {
                    throw new \InvalidArgumentException("Item ke-" . ($index + 1) . " melebihi sisa pesanan PO (Sisa: $sisaJatah).");
                }
                $sisaJatahSmall = $poItem->qty_small - $poItem->qty_received_small;
                if ($item['qty_received_small'] > $sisaJatahSmall) {
                    throw new \InvalidArgumentException("Item ke-" . ($index + 1) . " melebihi sisa pesanan PO (Sisa: $sisaJatahSmall).");
                }
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
            'status' => 'completed',
            'total_items' => $data['total_items'],
            'total_quantity_large' => $data['total_quantity_large'],
            'total_quantity_small' => $data['total_quantity_small'],
            'notes' => $data['notes'] ?? null,
        ]);
    }

    private function createGoodReceiptItem($goodReceiptId, $itemData, $locationId)
    {
        foreach ($itemData as $item) {
            $batch_number = $this->generateBatchNumber();

            $receipItems = GoodReceiptItem::create([
                'good_receipt_id' => $goodReceiptId,
                'product_id' => $item['product_id'],
                'batch_number' => $batch_number,
                'purchase_items_id' => $item['purchase_items_id'] ?? null,
                'qty_received_large' => $item['qty_received_large'],
                'qty_received_small' => $item['qty_received_small'],
                'qty_rejected_large' => $item['qty_rejected_large'] ?? 0,
                'qty_rejected_small' => $item['qty_rejected_small'] ?? 0,
                'reject_reason' => $item['reject_reason'] ?? null,
                'expiry_date' => $item['expiry_date'] ?? null,
                'note' => $item['note'] ?? null,
            ]);

            $this->createBatch($receipItems, $item, $locationId);
        }
    }


    private function createBatch($receipItems, $itemData, $locationId)
    {
        // Ambil data dari purchase item untuk harga dll
        $purchaseItem = PurchasesItems::find($receipItems->purchase_items_id);

        $batch = Batches::create([
            'product_id' => $receipItems->product_id,
            'batch_number' => $receipItems->batch_number,
            'purchase_price_large' => $purchaseItem->purchase_price_large ?? 0,
            'purchase_price_small' => $purchaseItem->purchase_price_small ?? 0,
            'quantity_large' => $receipItems->qty_received_large,
            'quantity_small' => $receipItems->qty_received_small,
            'selling_price_large' => $purchaseItem->selling_price_large ?? 0,
            'selling_price_small' => $purchaseItem->selling_price_small ?? 0,
            'expiry_date' => $receipItems->expiry_date,
            'status' => 'active',
        ]);

        BatchLocations::create([
            'batch_id' => $batch->id,
            'location_id' => $locationId,
            'quantity_large' => $receipItems->qty_received_large,
            'quantity_small' => $receipItems->qty_received_small,
        ]);
    }
}
