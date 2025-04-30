<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PenugasanPetugas;
use App\Models\Petugas;
use App\Models\JadwalOperasional;

class PenugasanPetugasSeeder extends Seeder
{
    public function run(): void
    {
        $petugasList = Petugas::all();
        $jadwalList = JadwalOperasional::all();

        if ($petugasList->isEmpty() || $jadwalList->isEmpty()) {
            $this->command->warn('Penugasan dibatalkan: petugas atau jadwal_operasional kosong.');
            return;
        }

        foreach ($jadwalList as $jadwal) {
            $petugasDipilih = $petugasList->random(rand(1, 2)); // random 1–2 petugas per jadwal

            foreach ($petugasDipilih as $petugas) {
                PenugasanPetugas::create([
                    'id_petugas' => $petugas->id,
                    'id_jadwal_operasional' => $jadwal->id,
                    'tugas' => rand(1, 2) // 1 = Driver, 2 = Picker misalnya
                ]);
            }
        }

        $this->command->info('Seeder Penugasan Petugas berhasil dijalankan.');
    }
}
