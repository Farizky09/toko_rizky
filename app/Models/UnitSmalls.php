<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class UnitSmalls extends Model
{
    use HasFactory;

    protected $table = 'unit_smalls';
    protected $fillable = ['name', 'abbreviation'];

    public function products()
    {
        return $this->hasMany(Products::class, 'unit_small_id');
    }
}
