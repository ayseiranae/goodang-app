<?php

namespace App\Http\Controllers;

use App\Models\TransaksiStok;
use App\Models\Barang;
use App\Models\Pemasok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

class TransaksiStokController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->input('tanggal');
        $search = $request->input('search');

        $query = TransaksiStok::with(['barang', 'pegawai', 'pemasok'])
            ->orderBy('created_at', 'desc');

        if ($tanggal) {
            $query->whereDate('created_at', $tanggal);
        }

        if ($search) {
            $query->whereHas('barang', function ($q) use ($search) {
                $q->where('barang', 'like', '%' . $search . '%');
            });
        }

        $transaksi = $query->get();

        if ($request->ajax()) {
            return view('transaksi.table_body', compact('transaksi', 'tanggal'))->render();
        }

        return view('transaksi.index', compact('transaksi', 'tanggal', 'search'));
    }

    public function create()
    {
        $barang = Barang::withSum('masuk', 'jumlah')
            ->withSum('keluar', 'jumlah')
            ->get();

        $pemasok = Pemasok::all();
        return view('transaksi.create', compact('barang', 'pemasok'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_barang' => 'required|exists:barang,id_barang',
            'transaksi' => 'required|in:masuk,keluar', 
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'required|string',
            'id_pemasok' => 'nullable|exists:pemasok,id_pemasok',
        ]);

        $idPemasok = $request->transaksi == 'masuk' ? $request->id_pemasok : null;

        if ($request->transaksi == 'masuk' && is_null($idPemasok)) {
            return back()->withInput()->withErrors(['id_pemasok' => 'Pemasok wajib diisi untuk barang MASUK.']);
        }

        TransaksiStok::create([
            'id_barang' => $request->id_barang,
            'id_pegawai' => Auth::id(), 
            'transaksi' => $request->transaksi,
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan,
            'id_pemasok' => $idPemasok,
        ]);

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dicatat.');
    }
}