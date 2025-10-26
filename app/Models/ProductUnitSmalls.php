<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductUnitSmalls extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'product_unit_smalls';
    protected $fillable = ['name', 'abbreviation'];
}
