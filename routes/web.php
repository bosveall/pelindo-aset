<?php

use App\Http\Controllers\AsetController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HakAksesController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PemeliharaanController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\RuanganController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// QR ruangan: halaman scan sengaja dapat dibuka tanpa login dari HP.
Route::get('/ruangan/{lokasi}', [RuanganController::class, 'show'])->name('ruangan.show');
Route::post('/ruangan/{lokasi}/pemeliharaan', [RuanganController::class, 'store'])->name('ruangan.pemeliharaan.store');

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Aset - All roles
    Route::resource('asets', AsetController::class);
    Route::view('/scan-barcode', 'asets.scan')->name('asets.scan');
    Route::get('/scan-barcode/hasil', [AsetController::class, 'scan'])->name('asets.scan.lookup');

    // Pemeliharaan - All roles
    Route::resource('pemeliharaans', PemeliharaanController::class);
    Route::get('/qr-ruangan', [RuanganController::class, 'index'])->name('ruangan.index');

    // Laporan - All roles
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/aset', [LaporanController::class, 'aset'])->name('laporan.aset');
    Route::get('/laporan/aset/pdf', [LaporanController::class, 'exportAsetPdf'])->name('laporan.aset.export.pdf');
    Route::get('/laporan/aset/excel', [LaporanController::class, 'exportAsetExcel'])->name('laporan.aset.export.excel');
    Route::get('/laporan/pemeliharaan', [LaporanController::class, 'pemeliharaan'])->name('laporan.pemeliharaan');
    Route::get('/laporan/pemeliharaan/pdf', [LaporanController::class, 'exportPemeliharaanPdf'])->name('laporan.pemeliharaan.export.pdf');
    Route::get('/laporan/pemeliharaan/excel', [LaporanController::class, 'exportPemeliharaanExcel'])->name('laporan.pemeliharaan.export.excel');

    // Admin only
    Route::middleware('role:admin')->group(function () {
        Route::get('/petugas', [PetugasController::class, 'index'])->name('petugas.index');
        Route::get('/petugas/create', [PetugasController::class, 'create'])->name('petugas.create');
        Route::post('/petugas', [PetugasController::class, 'store'])->name('petugas.store');
        Route::get('/petugas/{user}/edit', [PetugasController::class, 'edit'])->name('petugas.edit');
        Route::put('/petugas/{user}', [PetugasController::class, 'update'])->name('petugas.update');
        Route::delete('/petugas/{user}', [PetugasController::class, 'destroy'])->name('petugas.destroy');

        Route::get('/hak-akses', [HakAksesController::class, 'index'])->name('hakakses.index');
        Route::put('/hak-akses/{user}', [HakAksesController::class, 'update'])->name('hakakses.update');
    });
});
