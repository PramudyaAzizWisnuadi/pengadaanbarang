<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartemenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Departemen::create([
            'nama_departemen' => 'IT Department',
            'kode_departemen' => 'IT',
            'keterangan' => 'Departemen Teknologi Informasi'
        ]);

        \App\Models\Departemen::create([
            'nama_departemen' => 'Human Resources',
            'kode_departemen' => 'HR',
            'keterangan' => 'Departemen Sumber Daya Manusia'
        ]);

        \App\Models\Departemen::create([
            'nama_departemen' => 'Finance',
            'kode_departemen' => 'FIN',
            'keterangan' => 'Departemen Keuangan'
        ]);

        \App\Models\Departemen::create([
            'nama_departemen' => 'Operations',
            'kode_departemen' => 'OPS',
            'keterangan' => 'Departemen Operasional'
        ]);

        \App\Models\Departemen::create([
            'nama_departemen' => 'Marketing',
            'kode_departemen' => 'MKT',
            'keterangan' => 'Departemen Pemasaran'
        ]);
    }
}
