<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    // Menampilkan halaman jadual data (Data Table)
    public function index()
    {
        $locations = Location::all();
        return view('locations.index', compact('locations'));
    }

    // Menampilkan halaman form input
    public function create()
    {
        return view('locations.create');
    }

    // Menyimpan data dari form input ke database
    public function store(Request $request)
    {
        $request->validate([
            'location_name' => 'required|string|max:100',
            'max_motorcycle' => 'required|integer|min:0',
            'max_car' => 'required|integer|min:0',
            'max_other' => 'required|integer|min:0',
        ]);

        Location::create($request->all());

        // Setelah sukses simpan, redirect kembali ke halaman utama location
        return redirect('/locations')->with('success', 'Location created successfully!');
    }
}