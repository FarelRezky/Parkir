<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleType extends Model
{
    use HasFactory;

    // Baris ini sangat penting agar semua data dari form diizinkan masuk ke database
    protected $guarded = []; 
}