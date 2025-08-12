<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BarangPengadaan extends Model
{
    protected $fillable = [
        'pengadaan_barang_id',
        'kategori_barang_id',
        'nama_barang',
        'spesifikasi',
        'merk',
        'jumlah',
        'satuan',
        'harga_estimasi',
        'total_harga',
        'keterangan',
        'alasan_pengadaan',
        'prioritas'
    ];

    protected $casts = [
        'harga_estimasi' => 'decimal:2',
        'total_harga' => 'decimal:2',
        'jumlah' => 'integer',
        'prioritas' => 'integer'
    ];

    public function pengadaanBarang(): BelongsTo
    {
        return $this->belongsTo(PengadaanBarang::class);
    }

    public function kategoriBarang(): BelongsTo
    {
        return $this->belongsTo(KategoriBarang::class);
    }

    public function getPrioritasTextAttribute()
    {
        return match ($this->prioritas) {
            1 => 'Rendah',
            2 => 'Sedang',
            3 => 'Tinggi',
            default => 'Tidak Diketahui'
        };
    }
}
