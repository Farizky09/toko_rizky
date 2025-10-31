<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchLocations extends Model
{
    use HasFactory;
    protected $table = 'batch_locations';
    protected $fillable = [
        'batch_id',
        'location_id',
        'quantity_large',
        'quantity_small',
    ];

    public function batch()
    {
        return $this->belongsTo(Batches::class, 'batch_id');
    }
    public function location()
    {
        return $this->belongsTo(Locations::class, 'location_id');
    }
}
