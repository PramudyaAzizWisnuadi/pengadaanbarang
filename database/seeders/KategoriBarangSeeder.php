<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriBarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoris = [
            [
                'nama_kategori' => 'Komputer Desktop',
                'deskripsi' => 'Komputer desktop untuk keperluan kantor dan operasional',
                'is_active' => true
            ],
            [
                'nama_kategori' => 'Laptop',
                'deskripsi' => 'Laptop untuk keperluan mobile dan portabilitas',
                'is_active' => true
            ],
            [
                'nama_kategori' => 'Monitor',
                'deskripsi' => 'Monitor LCD, LED, dan layar tampilan lainnya',
                'is_active' => true
            ],
            [
                'nama_kategori' => 'Printer',
                'deskripsi' => 'Printer untuk kebutuhan cetak dokumen',
                'is_active' => true
            ],
            [
                'nama_kategori' => 'Scanner',
                'deskripsi' => 'Scanner untuk digitalisasi dokumen',
                'is_active' => true
            ],
            [
                'nama_kategori' => 'Keyboard & Mouse',
                'deskripsi' => 'Perangkat input keyboard dan mouse',
                'is_active' => true
            ],
            [
                'nama_kategori' => 'Storage',
                'deskripsi' => 'Perangkat penyimpanan seperti HDD, SSD, Flash Drive',
                'is_active' => true
            ],
            [
                'nama_kategori' => 'Network Equipment',
                'deskripsi' => 'Perangkat jaringan seperti router, switch, access point',
                'is_active' => true
            ],
            [
                'nama_kategori' => 'UPS & Power Supply',
                'deskripsi' => 'Perangkat power supply dan UPS untuk proteksi listrik',
                'is_active' => true
            ],
            [
                'nama_kategori' => 'Software',
                'deskripsi' => 'Lisensi software dan aplikasi',
                'is_active' => true
            ]
        ];

        foreach ($kategoris as $kategori) {
            \App\Models\KategoriBarang::create($kategori);
        }
    }
}
