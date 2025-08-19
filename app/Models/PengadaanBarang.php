<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PengadaanBarang extends Model
{
    protected $fillable = [
        'kode_pengadaan',
        'user_id',
        'departemen_id',
        'nama_pemohon',
        'jabatan',
        'departemen',
        'keterangan',
        'total_estimasi',
        'status',
        'skip_approval',
        'alasan_skip_approval',
        'tanggal_pengajuan',
        'tanggal_dibutuhkan',
        'tanggal_approval',
        'approved_by',
        'catatan_approval',
        'foto_approval',
        'file_ttd_atasan'
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'date',
        'tanggal_dibutuhkan' => 'date',
        'tanggal_approval' => 'datetime',
        'total_estimasi' => 'decimal:2',
        'skip_approval' => 'boolean'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function departemen(): BelongsTo
    {
        return $this->belongsTo(Departemen::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function barangPengadaan(): HasMany
    {
        return $this->hasMany(BarangPengadaan::class);
    }

    public static function generateKodePengadaan()
    {
        $prefix = 'PB-' . date('Ymd') . '-';
        $lastNumber = self::where('kode_pengadaan', 'like', $prefix . '%')
            ->orderBy('kode_pengadaan', 'desc')
            ->first();

        if ($lastNumber) {
            $number = intval(substr($lastNumber->kode_pengadaan, -4)) + 1;
        } else {
            $number = 1;
        }

        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
