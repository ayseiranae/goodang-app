<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'pegawai' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:pegawai'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $jumlahPegawai = Pegawai::count();

        if ($jumlahPegawai === 0) {
            $id_jabatan = 1;
        } else {
            $id_jabatan = 2;
        }

        $user = Pegawai::create([
            'pegawai' => $request->pegawai,
            'username' => $request->username,
            'id_jabatan' => $id_jabatan,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        session()->flash('success', 'Registrasi Berhasil! Selamat Datang.');

        return redirect()->route('dashboard');
    }
}