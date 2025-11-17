<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemasok extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'pemasok';
    protected $primaryKey = 'id_pemasok';

    protected $fillable = ['pemasok', 'kontak'];
}