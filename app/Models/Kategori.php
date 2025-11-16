<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;
public $timestamps = false;
    // Tentukan nama tabelnya secara manual
    protected $table = 'kategori';

    // Tentukan Primary Key-nya
    protected $primaryKey = 'id_kategori';

    // Kolom yang boleh diisi
    protected $fillable = ['kategori'];
}