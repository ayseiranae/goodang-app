<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiStok extends Model
{
    use HasFactory;

    protected $table = 'transaksi_stok';
    protected $primaryKey = 'id_transaksi';

    protected $fillable = [
        'id_barang',
        'id_pegawai',
        'transaksi', 
        'jumlah',
        'keterangan',
        'id_pemasok',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }

    public function pemasok()
    {
        return $this->belongsTo(Pemasok::class, 'id_pemasok', 'id_pemasok');
    }

    public function pegawai()
    {
        return $this->belongsTo(User::class, 'id_pegawai', 'id');
    }
}