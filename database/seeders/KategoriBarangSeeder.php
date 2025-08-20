<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\KategoriBarang;
use App\Models\Departemen;

class KategoriBarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get departments
        $departemenIT = Departemen::where('kode_departemen', 'IT')->first();
        $departemenHR = Departemen::where('kode_departemen', 'HR')->first();
        $departemenFIN = Departemen::where('kode_departemen', 'FIN')->first();
        $departemenMKT = Departemen::where('kode_departemen', 'MKT')->first();
        $departemenOPS = Departemen::where('kode_departemen', 'OPS')->first();

        $kategoris = [
            // IT Department Categories
            [
                'nama_kategori' => 'Komputer Desktop',
                'deskripsi' => 'Komputer desktop untuk keperluan kantor dan operasional',
                'departemen_id' => $departemenIT->id,
                'is_active' => true
            ],
            [
                'nama_kategori' => 'Laptop',
                'deskripsi' => 'Laptop untuk keperluan mobile dan portabilitas',
                'departemen_id' => $departemenIT->id,
                'is_active' => true
            ],
            [
                'nama_kategori' => 'Monitor',
                'deskripsi' => 'Monitor LCD, LED, dan layar tampilan lainnya',
                'departemen_id' => $departemenIT->id,
                'is_active' => true
            ],
            [
                'nama_kategori' => 'Printer',
                'deskripsi' => 'Printer untuk kebutuhan cetak dokumen',
                'departemen_id' => $departemenIT->id,
                'is_active' => true
            ],
            [
                'nama_kategori' => 'Scanner',
                'deskripsi' => 'Scanner untuk digitalisasi dokumen',
                'departemen_id' => $departemenIT->id,
                'is_active' => true
            ],
            [
                'nama_kategori' => 'Network Equipment',
                'deskripsi' => 'Perangkat jaringan seperti router, switch, access point',
                'departemen_id' => $departemenIT->id,
                'is_active' => true
            ],
            [
                'nama_kategori' => 'Server & Storage',
                'deskripsi' => 'Server dan perangkat penyimpanan enterprise',
                'departemen_id' => $departemenIT->id,
                'is_active' => true
            ],
            [
                'nama_kategori' => 'Software & Lisensi',
                'deskripsi' => 'Lisensi software dan aplikasi',
                'departemen_id' => $departemenIT->id,
                'is_active' => true
            ],

            // HR Department Categories
            [
                'nama_kategori' => 'Peralatan Training',
                'deskripsi' => 'Peralatan untuk keperluan training dan development',
                'departemen_id' => $departemenHR->id,
                'is_active' => true
            ],
            [
                'nama_kategori' => 'Furniture Kantor',
                'deskripsi' => 'Meja, kursi, lemari untuk ruang kerja',
                'departemen_id' => $departemenHR->id,
                'is_active' => true
            ],
            [
                'nama_kategori' => 'Alat Tulis Kantor',
                'deskripsi' => 'ATK untuk keperluan administrasi HR',
                'departemen_id' => $departemenHR->id,
                'is_active' => true
            ],
            [
                'nama_kategori' => 'Sistem Kehadiran',
                'deskripsi' => 'Perangkat absensi dan sistem kehadiran',
                'departemen_id' => $departemenHR->id,
                'is_active' => true
            ],

            // Finance Department Categories
            [
                'nama_kategori' => 'Kalkulator & Counting Machine',
                'deskripsi' => 'Kalkulator dan mesin hitung untuk finance',
                'departemen_id' => $departemenFIN->id,
                'is_active' => true
            ],
            [
                'nama_kategori' => 'Software Akuntansi',
                'deskripsi' => 'Software untuk keperluan akuntansi dan keuangan',
                'departemen_id' => $departemenFIN->id,
                'is_active' => true
            ],
            [
                'nama_kategori' => 'Safe & Security Box',
                'deskripsi' => 'Brankas dan kotak penyimpanan dokumen penting',
                'departemen_id' => $departemenFIN->id,
                'is_active' => true
            ],
            [
                'nama_kategori' => 'Banking Equipment',
                'deskripsi' => 'Peralatan untuk transaksi perbankan',
                'departemen_id' => $departemenFIN->id,
                'is_active' => true
            ],

            // Marketing Department Categories
            [
                'nama_kategori' => 'Camera & Photography',
                'deskripsi' => 'Kamera dan peralatan fotografi untuk promosi',
                'departemen_id' => $departemenMKT->id,
                'is_active' => true
            ],
            [
                'nama_kategori' => 'Design Equipment',
                'deskripsi' => 'Peralatan untuk keperluan design dan kreatif',
                'departemen_id' => $departemenMKT->id,
                'is_active' => true
            ],
            [
                'nama_kategori' => 'Printing & Banner',
                'deskripsi' => 'Peralatan cetak untuk material promosi',
                'departemen_id' => $departemenMKT->id,
                'is_active' => true
            ],
            [
                'nama_kategori' => 'Event Equipment',
                'deskripsi' => 'Peralatan untuk keperluan event dan exhibition',
                'departemen_id' => $departemenMKT->id,
                'is_active' => true
            ],

            // Operations Department Categories
            [
                'nama_kategori' => 'Production Tools',
                'deskripsi' => 'Peralatan untuk keperluan produksi',
                'departemen_id' => $departemenOPS->id,
                'is_active' => true
            ],
            [
                'nama_kategori' => 'Safety Equipment',
                'deskripsi' => 'Peralatan keselamatan kerja',
                'departemen_id' => $departemenOPS->id,
                'is_active' => true
            ],
            [
                'nama_kategori' => 'Maintenance Tools',
                'deskripsi' => 'Peralatan untuk maintenance dan repair',
                'departemen_id' => $departemenOPS->id,
                'is_active' => true
            ],
            [
                'nama_kategori' => 'Quality Control',
                'deskripsi' => 'Peralatan untuk quality control dan testing',
                'departemen_id' => $departemenOPS->id,
                'is_active' => true
            ],

            // General Affairs Categories
            [
                'nama_kategori' => 'Cleaning Supplies',
                'deskripsi' => 'Peralatan dan bahan pembersih',
                'departemen_id' => $departemenOPS->id,
                'is_active' => true
            ],
            [
                'nama_kategori' => 'Security System',
                'deskripsi' => 'Sistem keamanan dan CCTV',
                'departemen_id' => $departemenOPS->id,
                'is_active' => true
            ],
            [
                'nama_kategori' => 'Building Maintenance',
                'deskripsi' => 'Peralatan untuk maintenance gedung',
                'departemen_id' => $departemenOPS->id,
                'is_active' => true
            ],
            [
                'nama_kategori' => 'Transportation',
                'deskripsi' => 'Kendaraan dan peralatan transportasi',
                'departemen_id' => $departemenOPS->id,
                'is_active' => true
            ],
            [
                'nama_kategori' => 'Pantry & Kitchen',
                'deskripsi' => 'Peralatan dapur dan pantry',
                'departemen_id' => $departemenOPS->id,
                'is_active' => true
            ]
        ];

        foreach ($kategoris as $kategori) {
            KategoriBarang::create($kategori);
        }
    }
}
