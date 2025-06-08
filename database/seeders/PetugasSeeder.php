<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Petugas;
use App\Models\User;

class PetugasSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil user berdasarkan email atau nama tertentu
        $user = User::where('email', 'petugas@tunetic.com')->first();

        if (!$user) {
            $this->command->warn('User belum tersedia untuk penugasan petugas.');
            return;
        }

        Petugas::create([
            'user_id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'password' => $user->password, // bisa pakai ulang password-nya
            'nomor' => '081234567890',
            'tanggal_lahir' => '1995-05-10',
            'sim_image' => null,
            'alasan_bergabung' => 'Bergabung untuk bantu lingkungan.',
            'role' => 'Petugas',
            'email_verified_at' => now(),
        ]);
        // Ambil user berdasarkan email atau nama tertentu
        $user = User::where('email', 'petugas2@tunetic.com')->first();

        if (!$user) {
            $this->command->warn('User belum tersedia untuk penugasan petugas.');
            return;
        }

        Petugas::create([
            'user_id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'password' => $user->password, // bisa pakai ulang password-nya
            'nomor' => '081234567890',
            'tanggal_lahir' => '1995-05-10',
            'alamat' => $user->alamat,
            'sim_image' => null,
            'alasan_bergabung' => 'Bergabung untuk bantu lingkungan.',
            'role' => 'Petugas',
            'email_verified_at' => now(),
        ]);

        // // Ambil user berdasarkan email atau nama tertentu
        // $user = User::where('email', 'petugas3@resq.local')->first();

        // if (!$user) {
        //     $this->command->warn('User belum tersedia untuk penugasan petugas.');
        //     return;
        // }

        // Petugas::create([
        //     'user_id' => $user->id,
        //     'email' => $user->email,
        //     'name' => $user->name,
        //     'username' => 'petugasresq',
        //     'password' => $user->password, // bisa pakai ulang password-nya
        //     'nomor' => '081234567900',
        //     'tanggal_lahir' => '1995-05-10',
        //     'alamat' => $user->alamat,
        //     'sim_image' => null,
        //     'alasan_bergabung' => 'Bergabung untuk bantu lingkungan.',
        //     'role' => 'Petugas',
        //     'email_verified_at' => now(),
        // ]);
    }
}
