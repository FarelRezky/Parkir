<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Location;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // 1. Total Revenue Today
        $revenueToday = Transaction::whereDate('keluar', $today)
            ->sum('total_bayar');

        // 2. Vehicles Currently Parked
        $activeParked = Transaction::whereNull('keluar')->count();

        // 3. Total Transactions Today
        $totalTransactionsToday = Transaction::whereDate('masuk', $today)->count();

        // 4. Capacity Overview per Location
        $locations = Location::all();

        // 5. Recent Transactions
        $recentTransactions = Transaction::with(['location', 'vehicleType'])
            ->orderBy('masuk', 'desc')
            ->take(8)
            ->get();

        return view('dashboard.index', compact(
            'revenueToday',
            'activeParked',
            'totalTransactionsToday',
            'locations',
            'recentTransactions'
        ));
    }
}
