<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori; // <-- PENTING
use App\Models\Pemasok;  // <-- PENTING
use Illuminate\Http\Request;

class BarangController extends Controller
{
    /**
     * Tampilkan semua barang.
     */
    public function index()
    {
        // 'with' berguna untuk mengambil relasi (Kategori & Pemasok)
        // Ini lebih efisien daripada query N+1
        $barang = Barang::with(['kategori', 'pemasok'])
            ->withSum('masuk', 'jumlah')      // Ini akan jadi 'masuk_sum_jumlah'
            ->withSum('keluar', 'jumlah')     // Ini akan jadi 'keluar_sum_jumlah'
            ->get();
        return view('barang.index', compact('barang'));
    }

    /**
     * Tampilkan form buat barang baru.
     */
    public function create()
    {
        // Ambil data untuk dropdown
        $kategori = Kategori::all();
        $pemasok = Pemasok::all();
        return view('barang.create', compact('kategori', 'pemasok'));
    }

    /**
     * Simpan barang baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi
        $request->validate([
            'barang' => 'required|string|max:45',
            'deskripsi' => 'required|string',
            'satuan' => 'required|string|max:10', // Sesuaikan max-nya
            'id_kategori' => 'required|exists:kategori,id_kategori', // Pastikan ID-nya ada
            'id_pemasok' => 'required|exists:pemasok,id_pemasok', // Pastikan ID-nya ada
        ]);

        // Simpan data
        Barang::create($request->all());

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    /**
     * Tampilkan form untuk edit barang.
     */
    public function edit(Barang $barang) // Laravel otomatis cari barang pakai ID
    {
        // Ambil data untuk dropdown
        $kategori = Kategori::all();
        $pemasok = Pemasok::all();
        return view('barang.edit', compact('barang', 'kategori', 'pemasok'));
    }

    /**
     * Update barang di database.
     */
    public function update(Request $request, Barang $barang)
    {
        // Validasi
        $request->validate([
            'barang' => 'required|string|max:45',
            'deskripsi' => 'required|string',
            'satuan' => 'required|string|max:10',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'id_pemasok' => 'required|exists:pemasok,id_pemasok',
        ]);

        // Update data
        $barang->update($request->all());

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui.');
    }

    /**
     * Hapus barang dari database.
     */
    public function destroy(Barang $barang)
    {
        // Hati-hati: Sebaiknya cek dulu apakah barang ini ada di transaksi
        // Tapi untuk sekarang, kita hapus langsung
        try {
            $barang->delete();
            return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Tangkap error jika barang tidak bisa dihapus (misal karena FK constraint)
            return redirect()->route('barang.index')->with('error', 'Barang tidak bisa dihapus, mungkin sudah ada di transaksi.');
        }
    }
}