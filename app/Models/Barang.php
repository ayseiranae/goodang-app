<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

<<<<<<< Updated upstream
    // Asumsi: kamu juga gak pakai timestamps di tabel barang
=======
    // Konfigurasi dasar
>>>>>>> Stashed changes
    public $timestamps = false;
    protected $table = 'barang';
    protected $primaryKey = 'id_barang';

<<<<<<< Updated upstream
    /**
     * Kolom yang boleh diisi.
     */
=======
    // Kolom yang bisa diisi mass assignment
>>>>>>> Stashed changes
    protected $fillable = [
        'barang',
        'deskripsi',
        'satuan',
        'stok',
        'harga',
        'id_kategori',
        'id_pemasok'
    ];

<<<<<<< Updated upstream
    /**
     * Relasi: Satu barang MILIK SATU Kategori.
     */
=======
    // ==========================
    // Relasi
    // ==========================

    // Barang -> Kategori
>>>>>>> Stashed changes
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

<<<<<<< Updated upstream
    /**
     * Relasi: Satu barang MILIK SATU Pemasok.
     */
=======
    // Barang -> Pemasok
>>>>>>> Stashed changes
    public function pemasok()
    {
        return $this->belongsTo(Pemasok::class, 'id_pemasok', 'id_pemasok');
    }

<<<<<<< Updated upstream
    // ==========================================================
    // !! TIGA FUNGSI BARU UNTUK HITUNG STOK !!
    // ==========================================================

    /**
     * Relasi: Satu barang punya BANYAK transaksi.
     */
=======
    // Barang -> Transaksi Stok
>>>>>>> Stashed changes
    public function transaksiStok()
    {
        return $this->hasMany(TransaksiStok::class, 'id_barang', 'id_barang');
    }

<<<<<<< Updated upstream
    /**
     * Relasi: Hanya transaksi MASUK.
     */
=======
    // Barang -> Transaksi Masuk
>>>>>>> Stashed changes
    public function masuk()
    {
        return $this->hasMany(TransaksiStok::class, 'id_barang', 'id_barang')
            ->where('transaksi', 'masuk');
    }

<<<<<<< Updated upstream
    /**
     * Relasi: Hanya transaksi KELUAR.
     */
=======
    // Barang -> Transaksi Keluar
>>>>>>> Stashed changes
    public function keluar()
    {
        return $this->hasMany(TransaksiStok::class, 'id_barang', 'id_barang')
            ->where('transaksi', 'keluar');
    }

    // ==========================
    // Accessor & Scope
    // ==========================

    // Format harga ke Rupiah
    public function getHargaFormattedAttribute()
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    // Scope: stok menipis (default < 10)
    public function scopeMenipis($query, $batas = 10)
    {
        return $query->where('stok', '<', $batas);
    }

    // Scope: stok habis
    public function scopeHabis($query)
    {
        return $query->where('stok', '=', 0);
    }
}
