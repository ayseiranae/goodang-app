<?php

namespace App\Http\Controllers;

use App\Models\Pemasok;
use Illuminate\Http\Request;

class PemasokController extends Controller
{
    public function index()
    {
        $pemasok = Pemasok::all();
        return view('pemasok.index', compact('pemasok'));
    }

    public function create()
    {
        return view('pemasok.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'pemasok' => 'required|string|max:45',
            'kontak' => 'required|string|max:15',
        ]);

        Pemasok::create([
            'pemasok' => $request->pemasok,
            'kontak' => $request->kontak,
        ]);

        return redirect()->route('pemasok.index')->with('success', 'Pemasok berhasil ditambahkan.');
    }

    public function edit(Pemasok $pemasok)
    {
        return view('pemasok.edit', compact('pemasok'));
    }

    public function update(Request $request, Pemasok $pemasok)
    {
        $request->validate([
            'pemasok' => 'required|string|max:45',
            'kontak' => 'required|string|max:15',
        ]);

        $pemasok->update([
            'pemasok' => $request->pemasok,
            'kontak' => $request->kontak,
        ]);

        return redirect()->route('pemasok.index')->with('success', 'Pemasok berhasil diperbarui.');
    }

    public function destroy(Pemasok $pemasok)
    {
        $pemasok->delete();
        return redirect()->route('pemasok.index')->with('success', 'Pemasok berhasil dihapus.');
    }

    public function getData()
    {
        $pemasok = Pemasok::all();
        return response()->json($pemasok);
    }

    public function storeAjax(Request $request)
    {
        $validated = $request->validate([
            'pemasok' => 'required|string|max:45',
            'kontak'  => 'required|string|max:15',
        ]);

        $pemasok = Pemasok::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pemasok berhasil ditambahkan.',
            'data'    => $pemasok,
        ]);
    }

    // === AJAX UPDATE ===
    public function updateAjax(Request $request, $id)
    {
        $validated = $request->validate([
            'pemasok' => 'required|string|max:45',
            'kontak'  => 'required|string|max:15',
        ]);

        $pemasok = Pemasok::findOrFail($id);
        $pemasok->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pemasok berhasil diperbarui.',
            'data'    => $pemasok,
        ]);
    }

    // === AJAX DELETE ===
    public function deleteAjax($id)
    {
        $pemasok = Pemasok::findOrFail($id);
        $pemasok->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pemasok berhasil dihapus.',
        ]);
    }

    // public function getData()
    // {
    //     $pemasok = Pemasok::all();
    //     return response()->json($pemasok);
    // }

}
