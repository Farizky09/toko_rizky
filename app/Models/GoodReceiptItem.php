<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodReceiptItem extends Model
{
    use HasFactory;

    protected $table = 'good_receipt_items';

    protected $fillable = [
        'good_receipt_id',
        'product_id',
        'batch_number',
        'purchase_items_id',
        'qty_received_large',
        'qty_received_small',
        'qty_rejected_large',
        'qty_rejected_small',
        'reject_reason',
        'expiry_date',
        'note',
    ];

    public function goodReceipt()
    {
        return $this->belongsTo(GoodReceipt::class, 'good_receipt_id');
    }

    /**
     * Relasi ke Product
     */
    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }

    /**
     * Relasi ke Purchase Item (Detail PO)
     * PENTING: Foreign key didefinisikan sebagai 'purchase_items_id'
     * sesuai dengan kolom di database Anda.b
     *
     */
    public function purchaseItem()
    {
        return $this->belongsTo(PurchasesItems::class, 'purchase_items_id');
    }
}
