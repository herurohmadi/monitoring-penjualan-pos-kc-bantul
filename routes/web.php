<?php

use App\Http\Controllers\AktivasiSellerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BankDataController;
use App\Http\Controllers\CanvasingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KunjunganController;
use App\Http\Controllers\MaintenanceController;



Route::get('/', [AuthController::class, 'index']);

// Route untuk tamu
Route::middleware('guest')->controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login');
});

// Route untuk user login
Route::middleware(['auth', 'inject.user', 'nocache'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ACTIVASI SELLER ROUTES
    Route::get('/aktivasi/create', [AktivasiSellerController::class, 'create'])->name('aktivasiseller');
    Route::post('/aktivasi/store', [AktivasiSellerController::class, 'store'])->name('aktivasiseller.store');
    Route::get('/aktivasi/{id}/edit', [AktivasiSellerController::class, 'edit'])->name('aktivasiseller.edit');
    Route::put('/aktivasi/{id}', [AktivasiSellerController::class, 'update'])->name('aktivasiseller.update');
    Route::delete('/aktivasi/{id}', [AktivasiSellerController::class, 'destroy'])->name('aktivasiseller.destroy');

    // CANVASING ROUTES
    Route::get('/canvasing/create', [CanvasingController::class, 'create'])->name('canvasing');
    Route::post('/canvasing/store', [CanvasingController::class, 'store'])->name('canvasing.store');
    Route::get('/canvasing/{id}/edit', [CanvasingController::class, 'edit'])->name('canvasing.edit');
    Route::put('/canvasing/{id}', [CanvasingController::class, 'update'])->name('canvasing.update');
    Route::delete('/canvasing/{id}', [CanvasingController::class, 'destroy'])->name('canvasing.destroy');

    // KUNJUNGAN ROUTES
    Route::get('/kunjungan/create', [KunjunganController::class, 'create'])->name('kunjungan');
    Route::post('/kunjungan/store', [KunjunganController::class, 'store'])->name('kunjungan.store');
    Route::get('/kunjungan/{id}/edit', [KunjunganController::class, 'edit'])->name('kunjungan.edit');
    Route::put('/kunjungan/{id}', [KunjunganController::class, 'update'])->name('kunjungan.update');
    Route::delete('/kunjungan/{id}', [KunjunganController::class, 'destroy'])->name('kunjungan.destroy');

    // DATA SAYA
    Route::get('/data-saya', [BankDataController::class, 'index'])->name('data.saya');
    Route::get('/laporan/download', [BankDataController::class, 'downloadLaporan'])->name('laporan.download');

    // MAINTENANCE
    Route::get('/maintenance', [MaintenanceController::class, 'maintenance'])->name('maintenance');
    // Route::get('/maintenance', [MaintenanceController::class, 'maintenance'])->name('maintenance.clear');
    // Route::post('/maintenance/clear-all', [MaintenanceController::class, 'clearAll'])->name('maintenance.clearAll');

    // GRAFIK
    Route::get('/grafik', [BankDataController::class, 'grafikTahunan'])->name('grafik');
    });

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
