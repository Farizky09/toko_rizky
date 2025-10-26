<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductUnitLarges extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'product_unit_larges';
    protected $fillable = ['name', 'abbreviation'];
}
