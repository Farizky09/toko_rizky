<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasesItems extends Model
{
    use HasFactory;

    protected $table = 'purchase_items';

    protected $fillable = [
        'purchase_id',
        'product_id',
        'purchase_price_large',
        'purchase_price_small',
        'selling_price_large',
        'selling_price_small',
        'qty_large',
        'qty_small',
        'subtotal',
        'qty_received_large',
        'qty_received_small',
        'expiry_date',
        'item_notes',
    ];

    /**
     * =======================
     *        RELATIONS
     * =======================
     */


    public function product()
    {
        return $this->belongsTo(Products::class);
    }
    public function purchases()
    {

        return $this->hasMany(Purchases::class);
    }
}
