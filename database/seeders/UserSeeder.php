<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'nama' => 'Admin Utama',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'), // Pastikan untuk hashing password
            'alamat' => 'Jl. Contoh No. 123',
            'role' => 'Admin',
            'nomor' => '0812345678',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
