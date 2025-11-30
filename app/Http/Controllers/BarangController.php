<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Pemasok;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        $barang = Barang::with(['kategori', 'pemasok'])
            ->withSum('masuk', 'jumlah')
            ->withSum('keluar', 'jumlah')
            ->get();
        return view('barang.index', compact('barang'));
    }

    public function create()
    {
        $kategori = Kategori::all();
        $pemasok = Pemasok::all();
        return view('barang.create', compact('kategori', 'pemasok'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang' => 'required|string|max:45',
            'deskripsi' => 'required|string',
            'satuan' => 'required|string|max:10',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'id_pemasok' => 'required|exists:pemasok,id_pemasok',
        ]);

        Barang::create($request->all());

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit(Barang $barang)
    {
        $kategori = Kategori::all();
        $pemasok = Pemasok::all();
        return view('barang.edit', compact('barang', 'kategori', 'pemasok'));
    }

    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'barang' => 'required|string|max:45',
            'deskripsi' => 'required|string',
            'satuan' => 'required|string|max:10',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'id_pemasok' => 'required|exists:pemasok,id_pemasok',
        ]);

        $barang->update($request->all());

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Barang $barang)
    {
        try {
            $barang->delete();
            return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('barang.index')->with('error', 'Barang tidak bisa dihapus, mungkin sudah ada di transaksi.');
        }
    }

    public function getData()
    {
        $barang = Barang::with(['kategori', 'pemasok'])
            ->withSum('masuk', 'jumlah')
            ->withSum('keluar', 'jumlah')
            ->get();

        return response()->json($barang);
    }

    public function storeAjax(Request $request)
    {
        $request->validate([
            'barang' => 'required|string|max:45',
            'deskripsi' => 'required|string',
            'satuan' => 'required|string|max:10',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'id_pemasok' => 'required|exists:pemasok,id_pemasok',
        ]);

        $barang = Barang::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil ditambahkan',
            'data' => $barang,
    ]);
    }

}