<?php

namespace App\Http\Controllers;

use App\Models\TransaksiStok;
use App\Models\Barang;
use App\Models\Pemasok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
            return view('transaksi.table_body', compact('transaksi'))->render();
        }

        $barang = Barang::withSum('masuk', 'jumlah')
            ->withSum('keluar', 'jumlah')
            ->get();

        $pemasok = Pemasok::all();

        return view('transaksi.index', compact('transaksi', 'tanggal', 'search', 'barang', 'pemasok'));
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

    public function storeAjax(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_barang' => 'required|exists:barang,id_barang',
            'transaksi' => 'required|in:masuk,keluar',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'required|string',
            'id_pemasok' => 'nullable|exists:pemasok,id_pemasok',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        if ($data['transaksi'] === 'masuk' && empty($data['id_pemasok'])) {
            return response()->json([
                'success' => false,
                'errors' => ['id_pemasok' => ['Pemasok wajib diisi untuk barang MASUK.']],
            ], 422);
        }

        $transaksi = TransaksiStok::create([
            'id_barang' => $data['id_barang'],
            'id_pegawai' => Auth::id(),
            'transaksi' => $data['transaksi'],
            'jumlah' => $data['jumlah'],
            'keterangan' => $data['keterangan'],
            'id_pemasok' => $data['transaksi'] === 'masuk' ? $data['id_pemasok'] : null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil dicatat.',
            'data' => $transaksi,
        ]);
    }
}
