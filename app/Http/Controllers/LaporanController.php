<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use App\Models\ProfilPerusahaan;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        $laporan = $this->queryRangkumanTransaksi($bulan, $tahun);

        $daftarTahun = range(date('Y'), date('Y') - 5);
        return view('laporan.index', compact('laporan', 'bulan', 'tahun', 'daftarTahun'));
    }

    public function downloadPDF(Request $request)
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        $profil = ProfilPerusahaan::first();
        $rangkuman = $this->queryRangkumanTransaksi($bulan, $tahun);
        $detail_transaksi = $this->queryDetailTransaksi($bulan, $tahun);
        $stok_saat_ini = $this->queryStokSaatIni();
        $laporan = $this->queryRangkumanTransaksi($bulan, $tahun);
        $user = Auth::user();
        $cetakOleh = $user ? $user->username : 'Pengguna Tidak Dikenal';
        $cetakWaktu = now();
        $periode = \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') . ' ' . $tahun;

        $namaFile = 'laporan-goodang-' . $bulan . '-' . $tahun . '.pdf';

        $pdf = Pdf::loadView('laporan.pdf', compact(
            'profil',
            'laporan',
            'rangkuman',
            'detail_transaksi',
            'stok_saat_ini',
            'bulan',
            'tahun',
            'periode',
            'cetakOleh',
            'cetakWaktu'
        ));

        return $pdf->download($namaFile);
    }

    private function queryRangkumanTransaksi($bulan, $tahun)
    {
        $stokAkumulasi = DB::table('transaksi_stok')
            ->select(
                'id_barang',
                DB::raw("SUM(CASE WHEN transaksi = 'masuk' THEN jumlah ELSE 0 END) as total_masuk_all"),
                DB::raw("SUM(CASE WHEN transaksi = 'keluar' THEN jumlah ELSE 0 END) as total_keluar_all")
            )
            ->groupBy('id_barang');

        return DB::table('transaksi_stok')
            ->join('barang', 'transaksi_stok.id_barang', '=', 'barang.id_barang')
            ->leftJoin(
                DB::raw('(' . $stokAkumulasi->toSql() . ') as akumulasi'),
                'barang.id_barang',
                '=',
                'akumulasi.id_barang'
            )
            ->whereMonth('transaksi_stok.created_at', $bulan)
            ->whereYear('transaksi_stok.created_at', $tahun)
            ->select(
                'barang.id_barang',
                'barang.barang as nama_barang',
                DB::raw("SUM(CASE WHEN transaksi = 'masuk' THEN jumlah ELSE 0 END) as total_masuk"),
                DB::raw("SUM(CASE WHEN transaksi = 'keluar' THEN jumlah ELSE 0 END) as total_keluar"),
                DB::raw('IFNULL(akumulasi.total_masuk_all, 0) - IFNULL(akumulasi.total_keluar_all, 0) as stok_saat_ini')
            )
            ->addBinding($stokAkumulasi->getBindings())
            ->groupBy('barang.id_barang', 'barang.barang', 'akumulasi.total_masuk_all', 'akumulasi.total_keluar_all')
            ->get();
    }

    private function queryDetailTransaksi($bulan, $tahun)
    {
        return DB::table('transaksi_stok')
            ->join('barang', 'transaksi_stok.id_barang', '=', 'barang.id_barang')
            ->join('pegawai', 'transaksi_stok.id_pegawai', '=', 'pegawai.id_pegawai')
            ->whereMonth('transaksi_stok.created_at', $bulan)
            ->whereYear('transaksi_stok.created_at', $tahun)
            ->select(
                'transaksi_stok.created_at as tanggal',
                'barang.barang as nama_barang',
                'transaksi_stok.transaksi',
                'transaksi_stok.jumlah',
                'transaksi_stok.keterangan',
                'pegawai.pegawai as nama_pegawai'
            )
            ->orderBy('transaksi_stok.created_at', 'asc')
            ->get();
    }

    private function queryStokSaatIni()
    {
        $stok_akumulasi = DB::table('transaksi_stok')
            ->select(
                'id_barang',
                DB::raw("SUM(CASE WHEN transaksi = 'masuk' THEN jumlah ELSE 0 END) as total_masuk"),
                DB::raw("SUM(CASE WHEN transaksi = 'keluar' THEN jumlah ELSE 0 END) as total_keluar")
            )
            ->groupBy('id_barang');
        return DB::table('barang')
            ->leftJoin(
                DB::raw('(' . $stok_akumulasi->toSql() . ') as akumulasi'),
                'barang.id_barang',
                '=',
                'akumulasi.id_barang'
            )
            ->select(
                'barang.barang as nama_barang',
                DB::raw('IFNULL(akumulasi.total_masuk, 0) - IFNULL(akumulasi.total_keluar, 0) as stok_saat_ini')
            )
            ->addBinding($stok_akumulasi->getBindings())
            ->get();
    }

    public function detailBarang($id_barang, Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        $detail = DB::table('transaksi_stok')
            ->join('barang', 'transaksi_stok.id_barang', '=', 'barang.id_barang')
            ->join('pegawai', 'transaksi_stok.id_pegawai', '=', 'pegawai.id_pegawai')
            ->where('transaksi_stok.id_barang', $id_barang)
            ->when($bulan, function ($q) use ($bulan) {
                $q->whereMonth('transaksi_stok.created_at', $bulan);
            })
            ->when($tahun, function ($q) use ($tahun) {
                $q->whereYear('transaksi_stok.created_at', $tahun);
            })
            ->select(
                'transaksi_stok.created_at as tanggal',
                'barang.barang as nama_barang',
                'transaksi_stok.transaksi',
                'transaksi_stok.jumlah',
                'transaksi_stok.keterangan',
                'pegawai.pegawai as nama_pegawai'
            )
            ->orderBy('transaksi_stok.created_at', 'asc')
            ->get();

        return response()->json($detail);
    }
}
