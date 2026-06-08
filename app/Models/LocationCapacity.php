<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocationCapacity extends Model
{
    protected $fillable = ['location_id', 'vehicle_type_id', 'max_capacity'];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class);
    }
}
