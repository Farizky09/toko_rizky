<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchases extends Model
{
    use HasFactory;

    protected $table = 'purchases';
    protected $fillable = [
        'purchase_number',
        'branch_id',
        'location_id',
        'supplier_id',
        'user_id',
        'purchase_date',
        'total_items',
        'total_quantity_large',
        'total_quantity_small',
        'subtotal',
        'tax',
        'discount',
        'total_amount',
        'status',
        'notes',
    ];

    /**
     * =======================
     *        RELATIONS
     * =======================
     */

    public function branch()
    {
        return $this->belongsTo(Branches::class);
    }

    public function location()
    {
        return $this->belongsTo(Locations::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Suppliers::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function purchasesItems()
    {
        return $this->hasMany(PurchasesItems::class);
    }
}
