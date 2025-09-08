<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';  // sesuaikan dengan nama tabel di DB
    protected $fillable = ['nama_barang', 'keterangan'];
}

