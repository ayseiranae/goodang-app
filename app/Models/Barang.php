<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    // Asumsi: kamu juga gak pakai timestamps di tabel barang
    public $timestamps = false;

    protected $table = 'barang';
    protected $primaryKey = 'id_barang';

    /**
     * Kolom yang boleh diisi.
     */
    protected $fillable = [
        'barang',
        'deskripsi',
        'satuan',
        'id_kategori',
        'id_pemasok'
    ];

    /**
     * Relasi: Satu barang MILIK SATU Kategori.
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    /**
     * Relasi: Satu barang MILIK SATU Pemasok.
     */
    public function pemasok()
    {
        return $this->belongsTo(Pemasok::class, 'id_pemasok', 'id_pemasok');
    }

    // ==========================================================
    // !! TIGA FUNGSI BARU UNTUK HITUNG STOK !!
    // ==========================================================

    /**
     * Relasi: Satu barang punya BANYAK transaksi.
     */
    public function transaksiStok()
    {
        return $this->hasMany(TransaksiStok::class, 'id_barang', 'id_barang');
    }

    /**
     * Relasi: Hanya transaksi MASUK.
     */
    public function masuk()
    {
        return $this->hasMany(TransaksiStok::class, 'id_barang', 'id_barang')
            ->where('transaksi', 'masuk');
    }

    /**
     * Relasi: Hanya transaksi KELUAR.
     */
    public function keluar()
    {
        return $this->hasMany(TransaksiStok::class, 'id_barang', 'id_barang')
            ->where('transaksi', 'keluar');
    }
}