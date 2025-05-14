<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JadwalOperasional;
use App\Models\Jadwal;
use App\Models\Armada;
use App\Models\Rute;
use Illuminate\Support\Carbon;

class JadwalOperasionalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $armadas = Armada::all();
        $rutes = Rute::all();
        $jadwals = Jadwal::all();

        if ($armadas->isEmpty() || $rutes->isEmpty() || $jadwals->isEmpty()) {
            $this->command->warn('Seeder dibatalkan: Data dasar tidak lengkap.');
            return;
        }

        foreach ($jadwals as $jadwal) {
            // for ($j = 0; $j < rand(1, 3); $j++) {
            //     JadwalOperasional::create([
            //         'id_armada' => $armadas->random()->id,
            //         'id_jadwal' => $jadwal->id,
            //         'id_rute' => $rutes->random()->id,
            //         'tanggal' => '2025-05-30',
            //         'jam_aktif' => Carbon::createFromTime(rand(6, 10), 0, 0)->format('H:i:s'),
            //         'status' => 1
            //     ]);
            // }
            JadwalOperasional::create([
                'id_armada' => $armadas->random()->id,
                'id_jadwal' => $jadwal->id,
                'id_rute' => $rutes->random()->id,
                'tanggal' => '2025-05-30',
                'jam_aktif' => Carbon::createFromTime(rand(6, 10), 0, 0)->format('H:i:s'),
                'status' => 1
            ]);
        }

        $this->command->info('Jadwal Operasional berhasil dibuat.');
    }

}
