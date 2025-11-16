<?php

namespace App\Http\Controllers;

use App\Models\TransaksiStok;
use App\Models\Barang;
use App\Models\Pemasok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- PENTING, untuk ambil ID pegawai

class TransaksiStokController extends Controller
{
    /**
     * Tampilkan halaman log transaksi.
     */
    public function index(Request $request)
    {
        // Ambil tanggal dari input form
        $tanggal = $request->input('tanggal');

        // Mulai query
        $query = TransaksiStok::with(['barang', 'pegawai', 'pemasok'])
            ->orderBy('created_at', 'desc');

        // 3. TAMBAHKAN IF INI
        // Kalau ada input tanggal, filter berdasarkan tanggal itu
        if ($tanggal) {
            $query->whereDate('created_at', $tanggal);
        }

        // Eksekusi query
        $transaksi = $query->get();

        // 4. KIRIM 'tanggal' KE VIEW
        return view('transaksi.index', compact('transaksi', 'tanggal'));
    }

    /**
     * Tampilkan form input transaksi baru.
     */
    public function create()
    {
        $barang = Barang::withSum('masuk', 'jumlah')
            ->withSum('keluar', 'jumlah')
            ->get();

        $pemasok = Pemasok::all();
        return view('transaksi.create', compact('barang', 'pemasok'));
    }

    /**
     * Simpan transaksi baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_barang' => 'required|exists:barang,id_barang',
            'transaksi' => 'required|in:masuk,keluar', // Pastikan valuenya cuma 2 ini
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'required|string',
            'id_pemasok' => 'nullable|exists:pemasok,id_pemasok',
        ]);

        // Cek: kalau 'keluar', 'id_pemasok' harus null
        $idPemasok = $request->transaksi == 'masuk' ? $request->id_pemasok : null;

        // Cek: kalau 'masuk' tapi 'id_pemasok' tidak diisi (opsional, tapi bagus)
        if ($request->transaksi == 'masuk' && is_null($idPemasok)) {
            return back()->withInput()->withErrors(['id_pemasok' => 'Pemasok wajib diisi untuk barang MASUK.']);
        }

        TransaksiStok::create([
            'id_barang' => $request->id_barang,
            'id_pegawai' => Auth::id(), // <-- ID pegawai yg lagi login
            'transaksi' => $request->transaksi,
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan,
            'id_pemasok' => $idPemasok,
        ]);

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dicatat.');
    }
}