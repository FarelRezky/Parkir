<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Location;
use App\Models\VehicleType;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
    // 1. Tampilan Dashboard
    public function index()
    {
        $locations = Location::all();
        $vehicleTypes = VehicleType::all();
        $tickets = Transaction::whereNull('keluar')->orderBy('masuk', 'desc')->take(5)->get();
        
        return view('transactions.index', compact('locations', 'vehicleTypes', 'tickets'));
    }

    // 2. Tampilan Semua Transaksi (Data Table)
    public function list()
    {
        $transactions = Transaction::orderBy('created_at', 'desc')->get();
        return view('transactions.list', compact('transactions'));
    }

    // 3. Proses Kendaraan Masuk
    public function enter(Request $request)
    {
        $request->validate([
            'id_lokasi' => 'required',
            'id_jenis' => 'required',
            'no_polisi' => 'required'
        ]);

        $jenis = VehicleType::findOrFail($request->id_jenis);
        
        // Generate No Tiket (TahunBulanTanggalJamMenitDetik + ID Lokasi)
        $no_tiket = date('YmdHis') . $request->id_lokasi;

        $transaction = Transaction::create([
            'id_lokasi' => $request->id_lokasi,
            'no_tiket' => $no_tiket,
            'no_polisi' => strtoupper($request->no_polisi),
            'id_jenis' => $request->id_jenis,
            'masuk' => Carbon::now(),
            'perjam_pertama' => $jenis->perjam_pertama,
            'perjam_berikutnya' => $jenis->perjam_berikutnya,
            'max_perhari' => $jenis->max_perhari,
        ]);

        // FITUR BARU: Generate dan Simpan file PDF fisik ke storage/app/public/tickets/
        $tiket_data = Transaction::with(['location', 'vehicleType'])->find($transaction->id);
        $pdf = Pdf::loadView('transactions.ticket', ['transaction' => $tiket_data]);
        Storage::put('public/tickets/' . $no_tiket . '.pdf', $pdf->output());

        return redirect('/transactions')->with('masuk_success', $transaction->id);
    }

    // 4. Proses Kendaraan Keluar
    public function exit(Request $request)
    {
        $transaction = Transaction::where('no_tiket', $request->no_tiket)->whereNull('keluar')->first();

        if (!$transaction) {
            return back()->with('error', 'Tiket tidak ditemukan atau kendaraan sudah keluar.');
        }

        $waktuMasuk = Carbon::parse($transaction->masuk);
        $waktuKeluar = Carbon::now();
        $totalMenit = $waktuMasuk->diffInMinutes($waktuKeluar);
        
        // ATURAN 1: 1 Menit = 1 Jam (Untuk simulasi ujian)
        $totalJam = $totalMenit;
        if ($totalJam < 1) {
            $totalJam = 1; 
        }

        // Hitung Total Bayar berdasarkan ATURAN 2 & 3
        if ($totalJam <= 24) {
            $totalBayar = $transaction->perjam_pertama;
            
            if ($totalJam > 1) {
                $totalBayar += ($totalJam - 1) * $transaction->perjam_berikutnya;
            }

            if ($totalBayar > $transaction->max_perhari) {
                $totalBayar = $transaction->max_perhari;
            }
        } else {
            $hari = floor($totalJam / 24); 
            $tarifDiskon = $transaction->max_perhari * 0.6; 
            $totalBayar = $hari * $tarifDiskon;
        }

        $transaction->update([
            'keluar' => $waktuKeluar,
            'total_jam' => $totalJam,
            'total_bayar' => $totalBayar
        ]);

        return redirect('/transactions')->with('keluar_success', $transaction);
    }

    // 5. Cetak PDF
    public function printTicket($id)
    {
        $transaction = Transaction::with(['location', 'vehicleType'])->findOrFail($id);
        $pdf = Pdf::loadView('transactions.ticket', compact('transaction'));
        
        // Mengunduh langsung PDF-nya
        return $pdf->download('Tiket_'.$transaction->no_tiket.'.pdf');
    }
}