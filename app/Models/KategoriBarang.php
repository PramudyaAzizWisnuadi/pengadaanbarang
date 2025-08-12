<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriBarang extends Model
{
    protected $fillable = [
        'nama_kategori',
        'deskripsi',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function barangPengadaan(): HasMany
    {
        return $this->hasMany(BarangPengadaan::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
