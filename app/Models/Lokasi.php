<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lokasi extends Model
{
    protected $table = 'lokasi';
    protected $fillable = ['nama_lokasi', 'nama_divisi'];

    public function barang(): HasMany
    {
        return $this->hasMany(Barang::class);
    }
}
