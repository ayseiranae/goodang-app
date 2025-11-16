<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Pegawai extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $timestamps = false;
    protected $table = 'pegawai';
    protected $primaryKey = 'id_pegawai';

    protected $fillable = [
        'pegawai',
        'username',
        'password',
        'id_jabatan',
    ];

    protected $hidden = [
        'password',
    ];

    public function getAuthPassword()
    {
        return $this->password;
    }

    // ==========================================================
    // !! TAMBAHKAN 2 RELASI BARU DI BAWAH INI !!
    // ==========================================================

    /**
     * Relasi: Satu pegawai MILIK SATU Jabatan.
     */
    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan', 'id_jabatan');
    }

    /**
     * Relasi: Satu pegawai punya BANYAK Transaksi.
     * (Ini penting biar kita bisa cek sebelum hapus pegawai)
     */
    public function transaksiStok()
    {
        return $this->hasMany(TransaksiStok::class, 'id_pegawai', 'id_pegawai');
    }
}