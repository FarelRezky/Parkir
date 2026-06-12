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
    // ─────────────────────────────────────────
    // Helper: pastikan folder tickets ada
    // ─────────────────────────────────────────
    private function ensureTicketFolder(): string
    {
        $folder = storage_path('app/public/tickets');

        if (!file_exists($folder)) {
            mkdir($folder, 0755, true);
        }

        return $folder;
    }

    // ─────────────────────────────────────────
    // Helper: tentukan kategori kendaraan
    // ─────────────────────────────────────────
    private function getVehicleCategory($jenis): string
    {
        $jenis = strtolower(trim($jenis));
        
        $motorcycleKeywords = ['motorcycle','motor','sepeda motor','mtr','bike','motorbike'];
        $carKeywords = ['car','mobil','sedan','mpv','suv','automobile'];
        
        foreach ($motorcycleKeywords as $kw) {
            if (strpos($jenis, $kw) !== false) return 'motorcycle';
        }
        foreach ($carKeywords as $kw) {
            if (strpos($jenis, $kw) !== false) return 'car';
        }
        return 'other';
    }

    // ─────────────────────────────────────────
    // Helper: generate & simpan PDF ke storage
    // ─────────────────────────────────────────
    private function saveTicketPdf(Transaction $transaction): void
    {
        $folder   = $this->ensureTicketFolder();
        $filePath = $folder . '/' . $transaction->no_tiket . '.pdf';

        // Hanya simpan kalau belum ada (hindari generate ulang)
        if (!file_exists($filePath)) {
            $pdf = Pdf::loadView('transactions.ticket', ['transaction' => $transaction]);
            $pdf->setPaper([0, 0, 226.77, 340.16]);
            $pdf->save($filePath);
        }
    }

    // ─────────────────────────────────────────
    // 1. Dashboard
    // ─────────────────────────────────────────
    public function index()
    {
        $locations    = Location::all();
        $vehicleTypes = VehicleType::all();
        
        // Menampilkan 5 transaksi terbaru (baik yang masih aktif maupun sudah keluar)
        // agar history harga bisa terlihat di sidebar dashboard.
        $tickets      = Transaction::orderBy('masuk', 'desc')
                            ->take(5)
                            ->get();

        return view('transactions.index', compact('locations', 'vehicleTypes', 'tickets'));
    }

    // ─────────────────────────────────────────
    // 2. Semua Transaksi
    // ─────────────────────────────────────────
    public function list()
    {
        $transactions = Transaction::orderBy('created_at', 'desc')->get();
        return view('transactions.list', compact('transactions'));
    }

    // ─────────────────────────────────────────
    // 3. Kendaraan Masuk
    // ─────────────────────────────────────────
    public function enter(Request $request)
    {
        $request->validate([
            'id_lokasi' => 'required',
            'id_jenis'  => 'required',
            'no_polisi' => 'nullable',
        ]);

        $jenis    = VehicleType::findOrFail($request->id_jenis);
        $no_tiket = date('YmdHis') . $request->id_lokasi;
        $lokasi   = Location::findOrFail($request->id_lokasi);

        $kategori = $this->getVehicleCategory($jenis->jenis);

        if ($kategori === 'motorcycle') {
            if ($lokasi->max_motorcycle <= 0) return back()->with('error', 'Kapasitas motor penuh!');
            $lokasi->decrement('max_motorcycle');
        } elseif ($kategori === 'car') {
            if ($lokasi->max_car <= 0) return back()->with('error', 'Kapasitas mobil penuh!');
            $lokasi->decrement('max_car');
        } else {
            if ($lokasi->max_other <= 0) return back()->with('error', 'Kapasitas kendaraan lain penuh!');
            $lokasi->decrement('max_other');
        }

        $transaction = Transaction::create([
            'id_lokasi'         => $request->id_lokasi,
            'no_tiket'          => $no_tiket,
            'no_polisi'         => strtoupper($request->no_polisi),
            'id_jenis'          => $request->id_jenis,
            'masuk'             => Carbon::now(),
            'perjam_pertama'    => $jenis->perjam_pertama,
            'perjam_berikutnya' => $jenis->perjam_berikutnya,
            'max_perhari'       => $jenis->max_perhari,
        ]);

        // Load relasi agar view tiket lengkap
        $transaction->load(['location', 'vehicleType']);

        // Simpan PDF ke storage/app/public/tickets/{no_tiket}.pdf
        $this->saveTicketPdf($transaction);

        return redirect()->route('transactions.index')
                         ->with('masuk_success', $transaction->id);
    }

    // ─────────────────────────────────────────
    // 4. Kendaraan Keluar
    // ─────────────────────────────────────────
    public function exit(Request $request)
    {
        $transaction = Transaction::where('no_tiket', $request->no_tiket)
            ->whereNull('keluar')
            ->first();

        if (!$transaction) {
            return back()->with('error', 'Tiket tidak ditemukan atau kendaraan sudah keluar.');
        }

        $lokasi = Location::findOrFail($transaction->id_lokasi);
        $jenis = VehicleType::findOrFail($transaction->id_jenis);
        $kategori = $this->getVehicleCategory($jenis->jenis);

        if ($kategori === 'motorcycle') {
            $lokasi->increment('max_motorcycle');
        } elseif ($kategori === 'car') {
            $lokasi->increment('max_car');
        } else {
            $lokasi->increment('max_other');
        }

        $waktuMasuk  = Carbon::parse($transaction->masuk);
        $waktuKeluar = Carbon::now();
        $totalMenit  = max(1, $waktuMasuk->diffInMinutes($waktuKeluar));

        if ($totalMenit <= 1440) {
            $totalBayar = $transaction->perjam_pertama
                + ($transaction->perjam_berikutnya * ($totalMenit - 1));

            if ($totalBayar > $transaction->max_perhari) {
                $totalBayar = $transaction->max_perhari;
            }
        } else {
            $totalHari   = floor($totalMenit / 1440);
            $tarifHarian = $transaction->max_perhari * 0.6;
            $totalBayar  = $totalHari * $tarifHarian;
        }

        $transaction->update([
            'keluar'      => $waktuKeluar,
            'total_jam'   => $totalMenit,
            'total_bayar' => $totalBayar,
        ]);

        return redirect()->route('transactions.index')
                         ->with('keluar_success', $transaction);
    }

    // ─────────────────────────────────────────
    // 5. Download PDF (tombol download eksplisit)
    // ─────────────────────────────────────────
    public function viewTicket($id)
    {
        $transaction = Transaction::with(['location', 'vehicleType'])->findOrFail($id);
        
        // Sesuaikan 'pdf.ticket' dengan nama view blade untuk layout PDF Anda
        $pdf = Pdf::loadView('transactions.ticket', compact('transaction'));
        $pdf->setPaper([0, 0, 226.77, 340.16]);
        
        // Stream menampilkan di browser, bukan langsung download
        return $pdf->stream('Tiket_' . $transaction->no_tiket . '.pdf');
    }

    /**
     * Mengunduh PDF (tetap dipertahankan sesuai route Anda)
     */
    public function printTicket($id)
    {
        $transaction = Transaction::with(['location', 'vehicleType'])->findOrFail($id);
        $pdf = Pdf::loadView('transactions.ticket', compact('transaction'));
        $pdf->setPaper([0, 0, 226.77, 340.16]);
        
        return $pdf->download('Tiket_' . $transaction->no_tiket . '.pdf');
    }
}