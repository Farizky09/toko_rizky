<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $fillable = [
        'code',
        'name',
        'category_id',
        'unit_large_id',
        'unit_small_id',
        'conversion',
        'min_stock',
        'status',
        'description',
    ];

    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }
    public function unitLarge()
    {
        return $this->belongsTo(UnitLarges::class, 'unit_large_id');
    }
    public function unitSmall()
    {
        return $this->belongsTo(UnitSmalls::class, 'unit_small_id');
    }
}
