<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf; // <-- 1. PASTIKAN INI ADA

class LaporanController extends Controller
{
    /**
     * Tampilkan halaman laporan bulanan.
     */
    public function index(Request $request)
    {
        // 2. KITA UBAH INI
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        // Panggil fungsi query terpisah (Query yg tadi kamu duplikat, kita hapus)
        $laporan = $this->queryLaporan($bulan, $tahun);

        $daftarTahun = range(date('Y'), date('Y') - 5);
        return view('laporan.index', compact('laporan', 'bulan', 'tahun', 'daftarTahun'));
    }

    // 3. TAMBAHKAN FUNGSI BARU INI
    /**
     * Fungsi baru untuk Download PDF.
     */
    public function downloadPDF(Request $request)
    {
        // Ambil filter bulan & tahun
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        // Jalankan query yang sama
        $laporan = $this->queryLaporan($bulan, $tahun);

        // Buat nama file
        $namaFile = 'laporan-gudang-' . $bulan . '-' . $tahun . '.pdf';

        // "Muat" view PDF yang tadi kita bikin di Langkah 3
        $pdf = Pdf::loadView('laporan.pdf', compact('laporan', 'bulan', 'tahun'));

        // Suruh browser download file-nya
        return $pdf->download($namaFile);
    }

    // 4. TAMBAHKAN FUNGSI QUERY INI
    /**
     * Private function untuk query (biar nggak nulis 2x)
     */
    private function queryLaporan($bulan, $tahun)
    {
        return DB::table('transaksi_stok')
            ->join('barang', 'transaksi_stok.id_barang', '=', 'barang.id_barang')
            ->whereMonth('transaksi_stok.created_at', $bulan)
            ->whereYear('transaksi_stok.created_at', $tahun)
            ->select(
                'barang.barang as nama_barang',
                DB::raw("SUM(CASE WHEN transaksi = 'masuk' THEN jumlah ELSE 0 END) as total_masuk"),
                DB::raw("SUM(CASE WHEN transaksi = 'keluar' THEN jumlah ELSE 0 END) as total_keluar")
            )
            ->groupBy('barang.id_barang', 'barang.barang')
            ->get();
    }
}