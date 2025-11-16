<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemasok extends Model
{
    use HasFactory;

    // Bilang ke Laravel jangan cari created_at & updated_at
    public $timestamps = false;

    // Tentukan nama tabel & primary key
    protected $table = 'pemasok';
    protected $primaryKey = 'id_pemasok';

    // Kolom yang boleh diisi
    protected $fillable = ['pemasok', 'kontak'];
}