<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class PegawaiController extends Controller
{

    public function index()
    {
        $pegawai = Pegawai::with('jabatan')->get(); 
        return view('pegawai.index', compact('pegawai'));
    }

    public function create()
    {
        $jabatan = Jabatan::all();
        return view('pegawai.create', compact('jabatan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pegawai' => ['required', 'string', 'max:255'],
            'id_jabatan' => ['required', 'exists:jabatan,id_jabatan'],
            'username' => ['required', 'string', 'max:255', 'unique:pegawai'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        Pegawai::create([
            'pegawai' => $request->pegawai,
            'id_jabatan' => $request->id_jabatan,
            'username' => $request->username,
            'password' => Hash::make($request->password), 
        ]);

        return redirect()->route('pegawai.index')->with('success', 'Pegawai baru berhasil ditambahkan.');
    }

    public function edit(Pegawai $pegawai)
    {
        $jabatan = Jabatan::all();
        return view('pegawai.edit', compact('pegawai', 'jabatan'));
    }

    public function update(Request $request, Pegawai $pegawai)
    {
        $request->validate([
            'pegawai' => ['required', 'string', 'max:255'],
            'id_jabatan' => ['required', 'exists:jabatan,id_jabatan'],
            'username' => ['required', 'string', 'max:255', Rule::unique('pegawai')->ignore($pegawai->id_pegawai, 'id_pegawai')],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()], 
        ]);

        $data = [
            'pegawai' => $request->pegawai,
            'id_jabatan' => $request->id_jabatan,
            'username' => $request->username,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $pegawai->update($data);

        return redirect()->route('pegawai.index')->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy(Pegawai $pegawai)
    {
        if ($pegawai->id_pegawai == Auth::id()) {
            return redirect()->route('pegawai.index')->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        if ($pegawai->transaksiStok()->exists()) {
            return redirect()->route('pegawai.index')->with('error', 'Pegawai tidak bisa dihapus karena memiliki riwayat transaksi.');
        }

        $pegawai->delete();
        return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil dihapus.');
    }
}