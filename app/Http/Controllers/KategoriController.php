<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = Kategori::all();
        return view('kategori.index', compact('kategori'));
    }

    public function create()
    {
        return view('kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori' => 'required|string|max:45'
        ]);

        Kategori::create([
            'kategori' => $request->kategori
        ]);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function storeAjax(Request $request)
    {
        $request->validate([
            'kategori' => 'required|string|max:45'
        ]);

        $kategori = Kategori::create([
            'kategori' => $request->kategori
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil ditambahkan!',
            'data' => $kategori
        ]);
    }

    public function edit(Kategori $kategori)
    {
        return view('kategori.edit', compact('kategori'));
    }

    public function updateAjax(Request $request, Kategori $kategori)
    {
        $request->validate([
            'kategori' => 'required|string|max:45'
        ]);

        $kategori->update([
            'kategori' => $request->kategori
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil diperbarui via AJAX!'
        ]);
    }

    public function destroy(Kategori $kategori)
    {
        $kategori->delete();
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}