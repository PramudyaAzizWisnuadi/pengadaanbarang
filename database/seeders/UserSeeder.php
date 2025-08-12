<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Aziz',
            'email' => 'staffitmd@gmail.com',
            'jabatan' => 'IT',
            'departemen' => 'IT Department',
            'password' => Hash::make('Murahsetiaphari'),
        ]);
    }
}
