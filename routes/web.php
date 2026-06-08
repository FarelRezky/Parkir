<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VehicleTypeController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;

// Root redirect ke transactions
Route::get('/', function () {
    return redirect()->route('transactions.index');
});

// Vehicle Type
Route::resource('vehicle-types', VehicleTypeController::class)->only(['index', 'create', 'store']);
Route::get('/vehicle-types/create', [VehicleTypeController::class, 'create']);

// Location
Route::resource('locations', LocationController::class)->only(['index', 'store']);
Route::get('/locations/create', [LocationController::class, 'create']);

// Transaction
Route::get('transactions',         [TransactionController::class, 'index'])->name('transactions.index');
Route::get('transactions/list',    [TransactionController::class, 'list'])->name('transactions.list');
Route::post('/transactions/enter', [TransactionController::class, 'enter'])->name('transactions.enter');
Route::post('/transactions/exit',  [TransactionController::class, 'exit'])->name('transactions.exit');

/*
 * FIX: Dua route PDF — urutan PENTING, ticket-view harus di atas ticket/{id}
 * agar Laravel tidak salah tangkap "ticket-view" sebagai {id} = "ticket-view"
 *
 *   /transactions/ticket-view/{id}  → stream (tampil di browser, ikon PDF merah)
 *   /transactions/ticket/{id}       → download (jika user ingin download langsung)
 */
Route::get('/transactions/ticket-view/{id}', [TransactionController::class, 'viewTicket'])
    ->name('transactions.ticket.view');

Route::get('/transactions/ticket/{id}', [TransactionController::class, 'printTicket'])
    ->name('transactions.ticket');

// Reports
Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('/location',    [ReportController::class, 'location'])->name('location');
    Route::get('/transaction', [ReportController::class, 'transaction'])->name('transaction');
});