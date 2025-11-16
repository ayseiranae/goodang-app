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
     * Sesuai ERD kamu: 'barang', 'deskripsi', 'satuan', 'id_kategori', 'id_pemasok'
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
        // Parameter: (Model, foreign_key, primary_key_di_tabel_kategori)
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    /**
     * Relasi: Satu barang MILIK SATU Pemasok.
     */
    public function pemasok()
    {
        // Parameter: (Model, foreign_key, primary_key_di_tabel_pemasok)
        return $this->belongsTo(Pemasok::class, 'id_pemasok', 'id_pemasok');
    }
}