<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodReceipt extends Model
{
    use HasFactory;

    protected $table = 'good_receipts';

    protected $fillable = [
        'gr_number',
        'purchase_id',
        'receipt_date',
        'supplier_id',
        'branch_id',
        'location_id',
        'received_by',
        'status',
        'total_items',
        'total_quantity_large',
        'total_quantity_small',
        'notes',
    ];

    public function items()
    {
        return $this->hasMany(GoodReceiptItem::class, 'good_receipt_id');
    }

    /**
     * Relasi ke Purchase Order (PO)
     */
    public function purchase()
    {
        return $this->belongsTo(Purchases::class, 'purchase_id');
    }

    /**
     * Relasi ke Supplier
     */
    public function supplier()
    {
        return $this->belongsTo(Suppliers::class, 'supplier_id');
    }

    /**
     * Relasi ke Branch (Cabang)
     */
    public function branch()
    {
        return $this->belongsTo(Branches::class, 'branch_id');
    }

    /**
     * Relasi ke Location (Gudang Penerimaan)
     */
    public function location()
    {
        return $this->belongsTo(Locations::class, 'location_id');
    }

    /**
     * Relasi ke User (Penerima Barang)
     * Menggunakan foreign key 'received_by'
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}
