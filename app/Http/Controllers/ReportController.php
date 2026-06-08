<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\Transaction;

class ReportController extends Controller
{
    /**
     * Laporan Lokasi Parkir
     */
    public function location(Request $request)
    {
        $query = Location::query();

        // Filter pencarian (sesuai kolom yg ada: location_name)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('location_name', 'like', "%{$search}%");
            });
        }

        $locations = $query->paginate(15);

        // Hitung total kapasitas dari kolom yang ada
        $totalLokasi      = Location::count();
        $totalMaxMotor    = Location::sum('max_motorcycle');
        $totalMaxMobil    = Location::sum('max_car');
        $totalMaxOther    = Location::sum('max_other');
        $totalKapasitas   = $totalMaxMotor + $totalMaxMobil + $totalMaxOther;

        return view('reports.location', compact(
            'locations',
            'totalLokasi',
            'totalMaxMotor',
            'totalMaxMobil',
            'totalMaxOther',
            'totalKapasitas'
        ));
    }

    /**
     * Laporan Transaksi Parkir
     */
    public function transaction(Request $request)
    {
        $query = Transaction::with(['vehicleType', 'location']);

        // Filter pencarian (kolom: no_tiket, no_polisi)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_tiket', 'like', "%{$search}%")
                  ->orWhere('no_polisi', 'like', "%{$search}%");
            });
        }

        // Filter tanggal dari (kolom: masuk)
        if ($request->filled('date_from')) {
            $query->whereDate('masuk', '>=', $request->date_from);
        }

        // Filter tanggal sampai (kolom: masuk)
        if ($request->filled('date_to')) {
            $query->whereDate('masuk', '<=', $request->date_to);
        }

        // Filter status parkir (kolom: keluar)
        if ($request->filled('status')) {
            if ($request->status === 'aktif') {
                // Masih parkir = belum keluar
                $query->whereNull('keluar');
            } elseif ($request->status === 'selesai') {
                // Sudah keluar
                $query->whereNotNull('keluar');
            }
        }

        $transactions = $query->latest('masuk')->paginate(15);

        // Summary stats (kolom: keluar, total_bayar)
        $totalMasuk      = Transaction::count();
        $totalKeluar     = Transaction::whereNotNull('keluar')->count();
        $totalAktif      = Transaction::whereNull('keluar')->count();
        $totalPendapatan = Transaction::whereNotNull('total_bayar')->sum('total_bayar');

        return view('reports.transaction', compact(
            'transactions',
            'totalMasuk',
            'totalKeluar',
            'totalAktif',
            'totalPendapatan'
        ));
    }
}