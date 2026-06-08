<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VehicleTypeController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\TransactionController;

Route::get('/', function () {
    return redirect()->route('transactions.index');
});

Route::resource('vehicle-types', VehicleTypeController::class)->only(['index', 'create', 'store']);
Route::resource('locations', LocationController::class)->only(['index', 'store']);

Route::get('/locations/create', [LocationController::class, 'create']);

Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
Route::get('transactions/list', [TransactionController::class, 'list'])->name('transactions.list');

Route::post('/transactions/enter', [TransactionController::class, 'enter'])->name('transactions.enter');
Route::post('/transactions/exit', [TransactionController::class, 'exit'])->name('transactions.exit');
Route::get('/transactions/ticket/{id}', [TransactionController::class, 'printTicket']);

Route::get('/vehicle-types', [VehicleTypeController::class, 'index']);
Route::get('/vehicle-types/create', [VehicleTypeController::class, 'create']);
Route::post('/vehicle-types', [VehicleTypeController::class, 'store']);