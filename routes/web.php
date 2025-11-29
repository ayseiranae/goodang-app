<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PemasokController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\TransaksiStokController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\SettingController;
<<<<<<< Updated upstream
use App\Http\Controllers\ReportController;

=======
use App\Http\Controllers\DashboardController;
>>>>>>> Stashed changes

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

<<<<<<< Updated upstream

Route::get('/', fn() => view('welcome'));

Route::get('/dashboard', fn() => view('dashboard.index'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {

    // PROFILE
=======
// Redirect root ke login
Route::redirect('/', '/login');

// =======================
// DASHBOARD
// =======================

// Halaman utama dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Endpoint AJAX untuk dashboard
Route::middleware('auth')->prefix('dashboard')->group(function () {
    Route::get('/stok-ringkasan', [DashboardController::class, 'stokRingkasan'])->name('dashboard.stokRingkasan');
    Route::get('/stok-menipis', [DashboardController::class, 'stokMenipis'])->name('dashboard.stokMenipis');
    Route::get('/stok-terpopuler', [DashboardController::class, 'stokTerpopuler'])->name('dashboard.stokTerpopuler');
    Route::get('/transaksi-statistik', [DashboardController::class, 'transaksiStatistik'])->name('dashboard.transaksiStatistik');
    Route::get('/aktivitas-terbaru', [DashboardController::class, 'aktivitasTerbaru'])->name('dashboard.aktivitasTerbaru');

    // Tambahan: statistik stok per kategori
    Route::get('/stok-kategori', [DashboardController::class, 'stokPerKategori'])->name('dashboard.stokPerKategori');
});

// =======================
// FITUR DENGAN AUTH
// =======================
Route::middleware('auth')->group(function () {

    // Profile
>>>>>>> Stashed changes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // MASTER DATA
    Route::resource('kategori', KategoriController::class);
    Route::resource('pemasok', PemasokController::class);
    Route::resource('barang', BarangController::class);

<<<<<<< Updated upstream
    // TRANSAKSI
=======
    // Transaksi Stok (CRUD lengkap)
>>>>>>> Stashed changes
    Route::get('transaksi', [TransaksiStokController::class, 'index'])->name('transaksi.index');
    Route::get('transaksi/create', [TransaksiStokController::class, 'create'])->name('transaksi.create');
    Route::post('transaksi', [TransaksiStokController::class, 'store'])->name('transaksi.store');
    Route::get('transaksi/{id}/edit', [TransaksiStokController::class, 'edit'])->name('transaksi.edit');
    Route::put('transaksi/{id}', [TransaksiStokController::class, 'update'])->name('transaksi.update');
    Route::delete('transaksi/{id}', [TransaksiStokController::class, 'destroy'])->name('transaksi.destroy');

    // LAPORAN (umum)
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
<<<<<<< Updated upstream
    Route::get('laporan/pdf', [LaporanController::class, 'downloadPDF'])->name('laporan.pdf');
=======
    Route::get('laporan/pdf', [LaporanController::class, 'downloadPDF'])->name('laporan.downloadPDF');
    // Tambahan: export Excel & detail laporan
    Route::get('laporan/excel', [LaporanController::class, 'downloadExcel'])->name('laporan.downloadExcel');
    Route::get('laporan/{id}', [LaporanController::class, 'show'])->name('laporan.show');
>>>>>>> Stashed changes

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
