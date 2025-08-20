<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Departemen;

class DepartemenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departemens = [
            [
                'kode_departemen' => 'IT',
                'nama_departemen' => 'Information Technology',
                'kepala_departemen' => '',
                'deskripsi' => 'Departemen yang menangani teknologi informasi dan sistem komputer',
                'is_active' => true,
            ],
            [
                'kode_departemen' => 'HR',
                'nama_departemen' => 'Human Resource',
                'kepala_departemen' => 'Shani',
                'deskripsi' => 'Departemen yang menangani sumber daya manusia dan kepegawaian',
                'is_active' => true,
            ],
            [
                'kode_departemen' => 'FIN',
                'nama_departemen' => 'Finance',
                'kepala_departemen' => 'Susi',
                'deskripsi' => 'Departemen yang menangani keuangan dan akuntansi',
                'is_active' => true,
            ],
            [
                'kode_departemen' => 'MKT',
                'nama_departemen' => 'Marketing',
                'kepala_departemen' => 'Shani',
                'deskripsi' => 'Departemen yang menangani pemasaran dan promosi',
                'is_active' => true,
            ],
            [
                'kode_departemen' => 'OPS',
                'nama_departemen' => 'Operations',
                'kepala_departemen' => 'Tin',
                'deskripsi' => 'Departemen yang menangani operasional dan produksi',
                'is_active' => true,
            ],
            [
                'kode_departemen' => 'GA',
                'nama_departemen' => 'General Affairs',
                'kepala_departemen' => 'Shani',
                'deskripsi' => 'Departemen yang menangani urusan umum dan administrasi',
                'is_active' => true,
            ],
        ];

        foreach ($departemens as $departemen) {
            Departemen::create($departemen);
        }
    }
}
