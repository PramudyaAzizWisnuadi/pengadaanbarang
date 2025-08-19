<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Departemen;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the IT department
        $departemenIT = Departemen::where('kode_departemen', 'IT')->first();

        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@mdgroup.id',
            'jabatan' => 'Super Admin',
            'departemen_id' => $departemenIT->id,
            'departemen' => $departemenIT->nama_departemen,
            'role' => 'super_admin',
            'password' => Hash::make('Murahsetiaphari'),
        ]);

        // Create additional sample users for other departments
        $departemenHR = Departemen::where('kode_departemen', 'HR')->first();
        $departemenFIN = Departemen::where('kode_departemen', 'FIN')->first();
        $departemenMKT = Departemen::where('kode_departemen', 'MKT')->first();
        $departemenOPS = Departemen::where('kode_departemen', 'OPS')->first();
        $departemenGA = Departemen::where('kode_departemen', 'GA')->first();

        $sampleUsers = [
            [
                'name' => 'Aziz',
                'email' => 'staffit@mdgroup.id',
                'jabatan' => 'IT Staff',
                'departemen_id' => $departemenIT->id,
                'departemen' => $departemenIT->nama_departemen,
                'role' => 'admin',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Shani',
                'email' => 'hr@mdgroup.id',
                'jabatan' => 'HR Manager',
                'departemen_id' => $departemenHR->id,
                'departemen' => $departemenHR->nama_departemen,
                'role' => 'admin',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Susi',
                'email' => 'finance@mdgroup.id',
                'jabatan' => 'Finance Manager',
                'departemen_id' => $departemenFIN->id,
                'departemen' => $departemenFIN->nama_departemen,
                'role' => 'admin',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Diana Putri',
                'email' => 'marketing@mdgroup.id',
                'jabatan' => 'Marketing Manager',
                'departemen_id' => $departemenMKT->id,
                'departemen' => $departemenMKT->nama_departemen,
                'role' => 'admin',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Eko Prasetyo',
                'email' => 'operations@mdgroup.id',
                'jabatan' => 'Operations Manager',
                'departemen_id' => $departemenOPS->id,
                'departemen' => $departemenOPS->nama_departemen,
                'role' => 'admin',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Rina Marlina',
                'email' => 'ga@mdgroup.id',
                'jabatan' => 'GA Manager',
                'departemen_id' => $departemenGA->id,
                'departemen' => $departemenGA->nama_departemen,
                'role' => 'admin',
                'password' => Hash::make('password'),
            ]
        ];

        foreach ($sampleUsers as $userData) {
            User::create($userData);
        }
    }
}
