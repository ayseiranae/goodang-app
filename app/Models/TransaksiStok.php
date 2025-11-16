<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiStok extends Model
{
    use HasFactory;

    // Pakai timestamps (created_at) bawaan Laravel
    // public $timestamps = true; // Ini default, gak perlu ditulis

    protected $table = 'transaksi_stok';
    protected $primaryKey = 'id_transaksi';

    /**
     * Kolom yang boleh diisi.
     */
    protected $fillable = [
        'id_barang',
        'id_pegawai',
        'transaksi', // 'masuk' or 'keluar'
        'jumlah',
        'keterangan',
        'id_pemasok', // Nullable
    ];

    /**
     * Relasi: Satu transaksi MILIK SATU Barang.
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }

    /**
     * Relasi: Satu transaksi MILIK SATU Pemasok.
     */
    public function pemasok()
    {
        return $this->belongsTo(Pemasok::class, 'id_pemasok', 'id_pemasok');
    }

    /**
     * Relasi: Satu transaksi dicatat OLEH SATU Pegawai (User).
     */
    public function pegawai()
    {
        // Ingat, kita pakai tabel 'users' bawaan Breeze
        return $this->belongsTo(User::class, 'id_pegawai', 'id');
    }
}