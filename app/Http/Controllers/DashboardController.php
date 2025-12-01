<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Blade cuma kerangka, data di-load lewat AJAX
        return view('dashboard');
    }

    public function data()
    {
        // 1. Stok menipis (< 10)
        $lowStock = DB::table('barang')
            ->leftJoin('transaksi_stok', 'barang.id_barang', '=', 'transaksi_stok.id_barang')
            ->select(
                'barang.id_barang',
                'barang.barang',
                'barang.satuan',
                DB::raw("
                    COALESCE(
                        SUM(
                            CASE
                                WHEN transaksi_stok.transaksi = 'masuk'  THEN transaksi_stok.jumlah
                                WHEN transaksi_stok.transaksi = 'keluar' THEN -transaksi_stok.jumlah
                                ELSE 0
                            END
                        ), 0
                    ) as stok
                ")
            )
            ->groupBy('barang.id_barang', 'barang.barang', 'barang.satuan')
            ->having('stok', '<', 10)
            ->orderBy('stok')
            ->get();

        // 2. Aktivitas terakhir (10 transaksi stok terakhir)
        $recentTransactions = DB::table('transaksi_stok')
            ->join('barang', 'transaksi_stok.id_barang', '=', 'barang.id_barang')
            ->select(
                'transaksi_stok.id_transaksi',
                'barang.barang',
                'transaksi_stok.jumlah',
                'transaksi_stok.transaksi',
                'transaksi_stok.keterangan',
                'transaksi_stok.created_at'
            )
            ->orderByDesc('transaksi_stok.created_at')
            ->limit(10)
            ->get();

        // 3. Data grafik 7 hari terakhir
        $since = now()->subDays(7);

        $chartRows = DB::table('transaksi_stok')
            ->select(
                DB::raw('DATE(created_at) as tanggal'),
                DB::raw("
                    SUM(
                        CASE
                            WHEN transaksi = 'masuk'  THEN jumlah
                            WHEN transaksi = 'keluar' THEN -jumlah
                            ELSE 0
                        END
                    ) as total
                ")
            )
            ->where('created_at', '>=', $since)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('tanggal')
            ->get();

        $chartLabels = $chartRows->pluck('tanggal')->map(function ($date) {
            return Carbon::parse($date)->format('d-m');
        });
        $chartData = $chartRows->pluck('total');

        return response()->json([
            'low_stock'           => $lowStock,
            'recent_transactions' => $recentTransactions,
            'chart' => [
                'labels' => $chartLabels,
                'data'   => $chartData,
            ],
        ]);
    }

    public function searchBarang(Request $request)
    {
        $keyword = $request->query('q', '');

        $results = DB::table('barang')
            ->leftJoin('transaksi_stok', 'barang.id_barang', '=', 'transaksi_stok.id_barang')
            ->select(
                'barang.id_barang',
                'barang.barang',
                'barang.satuan',
                DB::raw("
                    COALESCE(
                        SUM(
                            CASE
                                WHEN transaksi_stok.transaksi = 'masuk'  THEN transaksi_stok.jumlah
                                WHEN transaksi_stok.transaksi = 'keluar' THEN -transaksi_stok.jumlah
                                ELSE 0
                            END
                        ), 0
                    ) as stok
                ")
            )
            ->when($keyword, function ($query) use ($keyword) {
                $query->where('barang.barang', 'like', '%' . $keyword . '%');
            })
            ->groupBy('barang.id_barang', 'barang.barang', 'barang.satuan')
            ->orderBy('barang.barang')
            ->limit(10)
            ->get();

        return response()->json($results);
    }
}
