<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use App\Models\Jadwal;
use App\Models\JadwalOperasional;
use App\Models\Armada;
use App\Models\RuteTps;

class JadwalSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan ada data armada dan rute_tps terlebih dahulu
        $armadas = Armada::all();
        $rutes   = RuteTps::all();

        if ($armadas->count() === 0 || $rutes->count() === 0) {
            $this->command->warn('Seeder dibatalkan: Pastikan tabel armada dan rute_tps memiliki data.');
            return;
        }

        // Hari dalam bahasa Indonesia
        $days = [
            'Sunday'    => 'Minggu',
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
        ];

        for ($i = 0; $i < 5; $i++) {
            $tanggal = Carbon::now()->addDays($i);
            $hari = $days[$tanggal->format('l')];

            $jadwal = Jadwal::create([
                'tanggal' => $tanggal->toDateString(),
                'hari' => $hari,
                'status' => 1
            ]);

            // Buat 1–3 jadwal operasional untuk setiap jadwal
            for ($j = 0; $j < rand(1, 3); $j++) {
                JadwalOperasional::create([
                    'id_armada' => $armadas->random()->id,
                    'id_jadwal' => $jadwal->id,
                    'id_rute_tps' => $rutes->random()->id,
                    'jam_aktif' => Carbon::createFromTime(rand(6, 10), 0, 0)->format('H:i:s')
                ]);
            }
        }

        $this->command->info('Seeder Jadwal dan Jadwal Operasional berhasil dijalankan.');
    }
}
