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

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Semua route aplikasi didefinisikan di sini.
|
*/

Route::redirect('/', '/login');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Master Data
    Route::resource('kategori', KategoriController::class);
    Route::resource('pemasok', PemasokController::class);
    Route::resource('barang', BarangController::class);

    // Transaksi Stok
    Route::get('transaksi', [TransaksiStokController::class, 'index'])->name('transaksi.index');
    Route::get('transaksi/create', [TransaksiStokController::class, 'create'])->name('transaksi.create');
    Route::post('transaksi', [TransaksiStokController::class, 'store'])->name('transaksi.store');

    // Laporan
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/pdf', [LaporanController::class, 'downloadPDF'])
        ->name('laporan.downloadPDF'); // konsisten dengan Blade

    // Admin Only
    Route::middleware('can:isAdmin')->group(function () {
        Route::resource('pegawai', PegawaiController::class);
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    });
});

require __DIR__ . '/auth.php';
