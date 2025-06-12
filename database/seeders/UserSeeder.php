<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // DB::table('users')->insert([
        //     'nama' => 'Admin Utama',
        //     'email' => 'admin@example.com',
        //     'password' => Hash::make('password123'), // Pastikan untuk hashing password
        //     'alamat' => 'Jl. Contoh No. 123',
        //     'role' => 'Admin',
        //     'nomor' => '0812345678',
        //     'email_verified_at' => now(),
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);

        $petugas1 = User::create([
            'name' => 'Petugas1 ResQ',
            'email' => 'petugas1@resq.local',
            'password' => Hash::make('password'),
            'province_id' => '11',
            'regency_id' => '1101',
            'district_id' => '110101',
            'village_id' => '1101012001',
            'level' => 3,
        ]);
        $petugas1->assignRole('petugas');

        $petugas2 = User::create([
            'name' => 'Petugas2 ResQ',
            'email' => 'petugas2@resq.local',
            'password' => Hash::make('password'),
            'province_id' => '11',
            'regency_id' => '1101',
            'district_id' => '110101',
            'village_id' => '1101012001',
            'level' => 3,
        ]);
        $petugas2->assignRole('petugas');

        $petugas3 = User::create([
            'name' => 'Petugas3 ResQ',
            'email' => 'petugas3@resq.local',
            'password' => Hash::make('password'),
            'province_id' => '11',
            'regency_id' => '1101',
            'district_id' => '110101',
            'village_id' => '1101012001',
            'alamat' => 'Jl. Contoh No. 123',
            'level' => 3,
        ]);
        $petugas3->assignRole('petugas');
    }
}
