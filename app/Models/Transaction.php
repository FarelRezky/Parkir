<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = []; // Mengizinkan semua kolom diisi massal

    // Relasi ke Location
    public function location() {
        return $this->belongsTo(Location::class, 'id_lokasi');
    }

    // Relasi ke Vehicle Type
    public function vehicleType() {
        return $this->belongsTo(VehicleType::class, 'id_jenis');
    }
}