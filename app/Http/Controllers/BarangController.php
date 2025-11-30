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
        return view('barang.index');
    }

    public function getData()
    {
        $barang = Barang::with(['kategori', 'pemasok'])
            ->withSum('masuk', 'jumlah')
            ->withSum('keluar', 'jumlah')
            ->get();

        return response()->json($barang);
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
            'barang' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'satuan' => 'required|string|max:50',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'id_pemasok' => 'required|exists:pemasok,id_pemasok',
        ]);

        Barang::create($request->all());

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    public function storeAjax(Request $request)
    {
        $request->validate([
            'barang' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'satuan' => 'required|string|max:50',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'id_pemasok' => 'required|exists:pemasok,id_pemasok',
        ]);

        $barang = Barang::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil ditambahkan!',
            'data' => $barang
        ]);
    }

    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        $kategori = Kategori::all();
        $pemasok = Pemasok::all();
        return view('barang.edit', compact('barang', 'kategori', 'pemasok'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'barang' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'satuan' => 'required|string|max:50',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'id_pemasok' => 'required|exists:pemasok,id_pemasok',
        ]);

        $barang = Barang::findOrFail($id);
        $barang->update($request->all());

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diupdate!');
    }

    public function updateAjax(Request $request, $id)
    {
        $request->validate([
            'barang' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'satuan' => 'required|string|max:50',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'id_pemasok' => 'required|exists:pemasok,id_pemasok',
        ]);

        $barang = Barang::findOrFail($id);
        $barang->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil diupdate!',
            'data' => $barang
        ]);
    }

    public function destroy($id)
    {
        try {
            $barang = Barang::findOrFail($id);
            $barang->delete();

            return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('barang.index')->with('error', 'Barang tidak bisa dihapus, mungkin sudah dipakai di transaksi!');
        }
    }

    public function deleteAjax($id)
    {
        try {
            $barang = Barang::findOrFail($id);
            $barang->delete();

            return response()->json([
                'success' => true,
                'message' => 'Barang berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Barang tidak bisa dihapus!'
            ], 500);
        }
    }
}