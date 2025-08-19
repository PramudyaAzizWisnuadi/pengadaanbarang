<?php

namespace Database\Seeders;

use App\Models\Departemen;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartemenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departemens = [
            [
                'nama_departemen' => 'Information Technology',
                'kode_departemen' => 'IT',
                'deskripsi' => 'Departemen yang menangani teknologi informasi dan sistem komputer',
                'kepala_departemen' => '',
                'is_active' => true,
            ],
            [
                'nama_departemen' => 'Human Resource',
                'kode_departemen' => 'HR',
                'deskripsi' => 'Departemen yang menangani sumber daya manusia dan kepegawaian',
                'kepala_departemen' => 'Shani',
                'is_active' => true,
            ],
            [
                'nama_departemen' => 'Finance',
                'kode_departemen' => 'FIN',
                'deskripsi' => 'Departemen yang menangani keuangan dan akuntansi',
                'kepala_departemen' => 'Susi',
                'is_active' => true,
            ],
            [
                'nama_departemen' => 'Marketing',
                'kode_departemen' => 'MKT',
                'deskripsi' => 'Departemen yang menangani pemasaran dan promosi',
                'kepala_departemen' => 'Shani',
                'is_active' => true,
            ],
            [
                'nama_departemen' => 'Operations',
                'kode_departemen' => 'OPS',
                'deskripsi' => 'Departemen yang menangani operasional dan produksi',
                'kepala_departemen' => 'Tin',
                'is_active' => true,
            ],
            [
                'nama_departemen' => 'General Affairs',
                'kode_departemen' => 'GA',
                'deskripsi' => 'Departemen yang menangani urusan umum dan administrasi',
                'kepala_departemen' => 'Shani',
                'is_active' => true,
            ],
        ];

        foreach ($departemens as $departemen) {
            Departemen::create($departemen);
        }
    }
}
