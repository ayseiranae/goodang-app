<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    // Ringkasan stok: jumlah barang & total stok
    public function stokRingkasan()
    {
        return response()->json([
            'totalBarang' => DB::table('barang')->count(),
            'totalStok'   => DB::table('barang')->sum('stok')
        ]);
    }

    // Barang stok menipis (< 10)
    public function stokMenipis()
    {
        return response()->json(
            DB::table('barang')
                ->leftJoin('transaksi_stok', 'barang.id_barang', '=', 'transaksi_stok.id_barang')
                ->select(
                    'barang.id_barang',
                    'barang.barang',
                    DB::raw('SUM(CASE WHEN transaksi_stok.transaksi = "masuk" THEN transaksi_stok.jumlah ELSE 0 END) -
                         SUM(CASE WHEN transaksi_stok.transaksi = "keluar" THEN transaksi_stok.jumlah ELSE 0 END) as stok')
                )
                ->groupBy('barang.id_barang', 'barang.barang')
                ->havingRaw('stok < 10')
                ->get()
        );
    }


    // Barang terpopuler berdasarkan transaksi, tampilkan nama barang
    public function stokTerpopuler()
    {
        return response()->json(
            DB::table('transaksi_stok')
                ->join('barang', 'transaksi_stok.id_barang', '=', 'barang.id_barang')
                ->select('barang.barang', DB::raw('COUNT(transaksi_stok.id_transaksi) as total'))
                ->groupBy('barang.barang')
                ->orderByDesc('total')
                ->limit(5)
                ->get()
        );
    }

    // Statistik transaksi per hari
    public function transaksiStatistik()
    {
        return response()->json(
            DB::table('transaksi_stok')
                ->selectRaw("
                    DATE(created_at) as tanggal,
                    SUM(CASE WHEN transaksi='masuk' THEN jumlah ELSE 0 END) as masuk,
                    SUM(CASE WHEN transaksi='keluar' THEN jumlah ELSE 0 END) as keluar
                ")
                ->groupBy('tanggal')
                ->orderBy('tanggal', 'desc')
                ->limit(7)
                ->get()
        );
    }

    // Aktivitas terbaru
    public function aktivitasTerbaru()
    {
        return response()->json(
            DB::table('transaksi_stok')
                ->join('pegawai', 'transaksi_stok.id_pegawai', '=', 'pegawai.id_pegawai')
                ->join('barang', 'transaksi_stok.id_barang', '=', 'barang.id_barang')
                ->orderByDesc('transaksi_stok.created_at')
                ->limit(5)
                ->select('transaksi_stok.*', 'pegawai.pegawai', 'barang.barang')
                ->get()
        );
    }
}
