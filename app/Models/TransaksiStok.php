<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiStok extends Model
{
    // ... (biarkan sisanya)

    // Satu transaksi milik satu barang
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }

    // Satu transaksi dicatat oleh satu pegawai (User)
    public function pegawai()
    {
        // Ingat, kita pakai tabel 'users' bawaan Breeze
        return $this->belongsTo(User::class, 'id_pegawai', 'id');
    }
}
