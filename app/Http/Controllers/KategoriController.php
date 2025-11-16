<?php

namespace App\Http\Controllers;

use App\Models\Kategori; // <-- TAMBAHKAN INI
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Tampilkan semua kategori.
     */
    public function index()
    {
        $kategori = Kategori::all(); // 1. Ambil semua data
        return view('kategori.index', compact('kategori')); // 2. Kirim ke view
    }

    /**
     * Tampilkan form buat kategori baru.
     */
    public function create()
    {
        return view('kategori.create');
    }

    /**
     * Simpan kategori baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'kategori' => 'required|string|max:45'
        ]);

        // 2. Simpan ke database
        Kategori::create([
            'kategori' => $request->kategori
        ]);

        // 3. Arahkan kembali ke halaman index
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Tampilkan form untuk edit kategori.
     */
    public function edit(Kategori $kategori) // Laravel otomatis cari data pakai ID
    {
        return view('kategori.edit', compact('kategori'));
    }

    /**
     * Update kategori di database.
     */
    public function update(Request $request, Kategori $kategori)
    {
        // 1. Validasi
        $request->validate([
            'kategori' => 'required|string|max:45'
        ]);

        // 2. Update data
        $kategori->update([
            'kategori' => $request->kategori
        ]);

        // 3. Redirect
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Hapus kategori dari database.
     */
    public function destroy(Kategori $kategori)
    {
        $kategori->delete();
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}