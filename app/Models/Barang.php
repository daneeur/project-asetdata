<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';
    protected $fillable = [
        'nama_barang',
        'kategori_id',
        'spesifikasi',
        'kondisi'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
}
