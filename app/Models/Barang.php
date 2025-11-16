<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    // ... (biarkan sisanya)

    // Satu barang milik satu kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    // Satu barang milik satu pemasok
    public function pemasok()
    {
        return $this->belongsTo(Pemasok::class, 'id_pemasok', 'id_pemasok');
    }

    // Satu barang punya banyak transaksi
    public function transaksi()
    {
        return $this->hasMany(TransaksiStok::class, 'id_barang', 'id_barang');
    }
}
