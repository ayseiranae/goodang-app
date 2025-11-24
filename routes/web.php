<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PemasokController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\TransaksiStokController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ReportController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', fn() => view('welcome'));

Route::get('/dashboard', fn() => view('dashboard.index'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {

    // PROFILE
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // MASTER DATA
    Route::resource('kategori', KategoriController::class);
    Route::resource('pemasok', PemasokController::class);
    Route::resource('barang', BarangController::class);

    // TRANSAKSI
    Route::get('transaksi', [TransaksiStokController::class, 'index'])->name('transaksi.index');
    Route::get('transaksi/create', [TransaksiStokController::class, 'create'])->name('transaksi.create');
    Route::post('transaksi', [TransaksiStokController::class, 'store'])->name('transaksi.store');

    // LAPORAN (umum)
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/pdf', [LaporanController::class, 'downloadPDF'])->name('laporan.pdf');

    // ADMIN ONLY
    Route::middleware(['can:isAdmin'])->group(function () {
        Route::resource('pegawai', PegawaiController::class);

        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');

        // REPORT (AJAX)
        Route::post('/reports/filter', [ReportController::class, 'filter'])->name('reports.filter');
        Route::post('/reports/export-pdf', [ReportController::class, 'exportPDF'])->name('reports.exportPDF');
        Route::get('/reports/statistics', [ReportController::class, 'getStatistics'])->name('reports.statistics');
    });
});

require __DIR__ . '/auth.php';
