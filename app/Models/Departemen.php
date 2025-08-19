<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Departemen extends Model
{
    protected $fillable = [
        'nama_departemen',
        'kode_departemen',
        'deskripsi',
        'kepala_departemen',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the users for the departemen.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the pengadaan barangs for the departemen.
     */
    public function pengadaanBarangs(): HasMany
    {
        return $this->hasMany(PengadaanBarang::class);
    }

    /**
     * Get the kategori barangs for the departemen.
     */
    public function kategoriBarangs(): HasMany
    {
        return $this->hasMany(KategoriBarang::class);
    }
}
