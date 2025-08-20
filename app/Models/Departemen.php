<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Departemen extends Model
{
    protected $fillable = [
        'kode_departemen',
        'nama_departemen',
        'kepala_departemen',
        'deskripsi',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Get the users for the departemen.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'departemen_id');
    }

    /**
     * Get the kategori barangs for the departemen.
     */
    public function kategoriBarangs(): HasMany
    {
        return $this->hasMany(KategoriBarang::class, 'departemen_id');
    }

    /**
     * Get the pengadaan barangs for the departemen.
     */
    public function pengadaanBarangs(): HasMany
    {
        return $this->hasMany(PengadaanBarang::class, 'departemen_id');
    }

    /**
     * Scope a query to only include active departemens.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
