<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locations extends Model
{
    use HasFactory;

    protected $table = 'locations';
    protected $fillable = [
        'branch_id',
        'name',
        'type',
    ];

    public function branch()
    {
        return $this->belongsTo(Branches::class);
    }

    public function batchLocations()
    {
        return $this->hasMany(BatchLocations::class, 'location_id');
    }
    public function purchases()
    {
        return $this->hasMany(Purchases::class);
    }
}
