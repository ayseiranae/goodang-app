<?php

namespace App\Http\Controllers;

use App\Models\Pemasok; 
use Illuminate\Http\Request;

class PemasokController extends Controller
{
    /**
     * Tampilkan semua pemasok.
     */
    public function index()
    {
        $pemasok = Pemasok::all(); // Ganti
        return view('pemasok.index', compact('pemasok')); // Ganti
    }

    /**
     * Tampilkan form buat pemasok baru.
     */
    public function create()
    {
        return view('pemasok.create'); // Ganti
    }

    /**
     * Simpan pemasok baru ke database.
     */
    public function store(Request $request)
    {
        // Ganti validasinya
        $request->validate([
            'pemasok' => 'required|string|max:45',
            'kontak' => 'required|string|max:15',
        ]);

        // Ganti create-nya
        Pemasok::create([
            'pemasok' => $request->pemasok,
            'kontak' => $request->kontak,
        ]);

        // Ganti redirect-nya
        return redirect()->route('pemasok.index')->with('success', 'Pemasok berhasil ditambahkan.');
    }

    /**
     * Tampilkan form untuk edit pemasok.
     */
    public function edit(Pemasok $pemasok) // Ganti
    {
        return view('pemasok.edit', compact('pemasok')); // Ganti
    }

    /**
     * Update pemasok di database.
     */
    public function update(Request $request, Pemasok $pemasok) // Ganti
    {
        // Ganti validasinya
        $request->validate([
            'pemasok' => 'required|string|max:45',
            'kontak' => 'required|string|max:15',
        ]);

        // Ganti update-nya
        $pemasok->update([
            'pemasok' => $request->pemasok,
            'kontak' => $request->kontak,
        ]);

        // Ganti redirect-nya
        return redirect()->route('pemasok.index')->with('success', 'Pemasok berhasil diperbarui.');
    }

    /**
     * Hapus pemasok dari database.
     */
    public function destroy(Pemasok $pemasok) // Ganti
    {
        $pemasok->delete();
        return redirect()->route('pemasok.index')->with('success', 'Pemasok berhasil dihapus.'); // Ganti
    }
}