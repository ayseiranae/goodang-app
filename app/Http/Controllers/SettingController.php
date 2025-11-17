<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProfilPerusahaan;

class SettingController extends Controller
{
    public function index()
    {
        $profil = ProfilPerusahaan::firstOrNew(['id_perusahaan' => 1]);
        return view('setting.index', compact('profil'));
    }
    public function update(Request $request)
    {
        $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|string|max:255',
        ]);
        ProfilPerusahaan::updateOrCreate(
            ['id_perusahaan' => 1],
            $request->only(['nama_perusahaan', 'alamat', 'telepon', 'email', 'website'])
        );

        return redirect()->back()->with('success', 'Profil perusahaan berhasil disimpan.');
    }
}
