<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // <-- PENTING UNTUK PASSWORD
use Illuminate\Support\Facades\Auth; // <-- PENTING UNTUK CEK DIRI SENDIRI
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class PegawaiController extends Controller
{
    /**
     * Tampilkan semua pegawai.
     */
    public function index()
    {
        $pegawai = Pegawai::with('jabatan')->get(); // Ambil data + jabatannya
        return view('pegawai.index', compact('pegawai'));
    }

    /**
     * Tampilkan form buat pegawai baru.
     */
    public function create()
    {
        $jabatan = Jabatan::all(); // Untuk dropdown
        return view('pegawai.create', compact('jabatan'));
    }

    /**
     * Simpan pegawai baru ke database.
     */
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
            'password' => Hash::make($request->password), // !! WAJIB DI-HASH !!
        ]);

        return redirect()->route('pegawai.index')->with('success', 'Pegawai baru berhasil ditambahkan.');
    }

    /**
     * Tampilkan form untuk edit pegawai.
     */
    public function edit(Pegawai $pegawai)
    {
        $jabatan = Jabatan::all();
        return view('pegawai.edit', compact('pegawai', 'jabatan'));
    }

    /**
     * Update pegawai di database.
     */
    public function update(Request $request, Pegawai $pegawai)
    {
        $request->validate([
            'pegawai' => ['required', 'string', 'max:255'],
            'id_jabatan' => ['required', 'exists:jabatan,id_jabatan'],
            'username' => ['required', 'string', 'max:255', Rule::unique('pegawai')->ignore($pegawai->id_pegawai, 'id_pegawai')],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()], // Boleh kosong
        ]);

        // Siapkan data update
        $data = [
            'pegawai' => $request->pegawai,
            'id_jabatan' => $request->id_jabatan,
            'username' => $request->username,
        ];

        // Cek: Kalau password diisi, hash password baru.
        // Kalau kosong, biarin password lama.
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $pegawai->update($data);

        return redirect()->route('pegawai.index')->with('success', 'Data pegawai berhasil diperbarui.');
    }

    /**
     * Hapus pegawai dari database.
     */
    public function destroy(Pegawai $pegawai)
    {
        // PENTING: Jangan biarkan admin hapus diri sendiri
        if ($pegawai->id_pegawai == Auth::id()) {
            return redirect()->route('pegawai.index')->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        // PENTING: Cek apakah pegawai punya transaksi
        if ($pegawai->transaksiStok()->exists()) {
            return redirect()->route('pegawai.index')->with('error', 'Pegawai tidak bisa dihapus karena memiliki riwayat transaksi.');
        }

        // Kalau aman, hapus
        $pegawai->delete();
        return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil dihapus.');
    }
}