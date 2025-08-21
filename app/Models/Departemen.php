<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Departemen extends Model
{
    use HasFactory;

    protected $table = 'departemens';

    protected $fillable = [
        'kode_departemen',
        'nama_departemen',
        'keterangan',
        'is_active'
    ];

    /**
     * Get the users for the departemen.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'departemen_id');
    }

    /**
     * Get the pengadaan barangs for the departemen.
     */
    public function pengadaanBarangs()
    {
        return $this->hasMany(PengadaanBarang::class, 'departemen_id');
    }

    /**
     * Get the kategori barangs for the departemen.
     */
    public function kategoriBarangs()
    {
        return $this->hasMany(KategoriBarang::class, 'departemen_id');
    }
}
