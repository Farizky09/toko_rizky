<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batches extends Model
{
    use HasFactory;

    protected $table = 'batches';

    protected $fillable = [
        'product_id',
        'batch_number',
        'purchase_price_large',
        'purchase_price_small',
        'quantity_large',
        'quantity_small',
        'selling_price_large',
        'selling_price_small',
        'expiry_date',
        'status',
    ];

    public function product()
    {
        return $this->belongsTo(Products::class);
    }
    public function batchLocations()
    {
        return $this->hasMany(BatchLocations::class, 'batch_id');
    }
}
