<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RuteTps;
use App\Models\Rute;
use App\Models\LokasiTps;

class RuteTpsSeeder extends Seeder
{
    public function run(): void
    {
        $rutes = Rute::all();
        $lokasis = LokasiTps::all();

        if ($rutes->isEmpty() || $lokasis->isEmpty()) {
            $this->command->warn('Seeder dibatalkan: Tabel rute atau lokasi_tps belum ada data.');
            return;
        }

        // Hubungkan beberapa TPS ke rute secara acak
        foreach ($rutes as $rute) {
            $lokasi_acak = $lokasis->random(rand(1, 3))->values(); // pastikan indexing dari 0
            foreach ($lokasi_acak as $index => $lokasi) {
                RuteTps::create([
                    'id_rute' => $rute->id,
                    'id_lokasi_tps' => $lokasi->id,
                    'urutan' => $index + 1 // dimulai dari 1
                ]);
            }
        }


        $this->command->info('Seeder Rute TPS berhasil dijalankan.');
    }
}
