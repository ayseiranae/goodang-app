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
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::redirect('/', '/login');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('kategori', KategoriController::class);
    Route::post('kategori/store-ajax', [KategoriController::class, 'storeAjax'])
    ->name('kategori.store.ajax');
    Route::post('kategori/update-ajax/{kategori}', [KategoriController::class, 'updateAjax'])
    ->name('kategori.update.ajax');
    Route::resource('pemasok', PemasokController::class);
    Route::resource('barang', BarangController::class);
    Route::get('barang/data', [BarangController::class, 'getData'])->name('barang.data');
    Route::get('transaksi', [TransaksiStokController::class, 'index'])->name('transaksi.index');
    Route::post('barang/store-ajax', [BarangController::class, 'storeAjax'])
    ->name('barang.store.ajax');
    Route::get('transaksi/create', [TransaksiStokController::class, 'create'])->name('transaksi.create');
    Route::post('transaksi', [TransaksiStokController::class, 'store'])->name('transaksi.store');
    Route::post('transaksi/store-ajax', [TransaksiStokController::class, 'storeAjax'])
    ->name('transaksi.store.ajax');
    Route::get('transaksi/barang-search', [TransaksiStokController::class, 'searchBarang'])
    ->name('transaksi.barang.search');
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/pdf', [LaporanController::class, 'downloadPDF'])
        ->middleware('auth')
        ->name('laporan.pdf');
    Route::middleware('can:isAdmin')->group(function () {
        Route::resource('pegawai', PegawaiController::class);
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    });
});

require __DIR__ . '/auth.php';