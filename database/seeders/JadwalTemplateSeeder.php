<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JadwalTemplate;
use App\Models\JadwalTemplatePetugas;
use App\Models\Armada;
use App\Models\Rute;
use App\Models\Petugas;

class JadwalTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $days = ['Senin', 'Senin', 'Senin', 'Senin', 'Senin', 'Selasa', 'Selasa', 'Selasa', 'Selasa', 'Selasa', 'Rabu', 'Rabu', 'Rabu', 'Rabu', 'Rabu', 'Kamis', 'Kamis', 'Kamis', 'Kamis', 'Kamis', 'Jumat', 'Jumat', 'Jumat', 'Jumat', 'Jumat', 'Sabtu', 'Sabtu', 'Sabtu', 'Sabtu', 'Sabtu', 'Minggu', 'Minggu', 'Minggu', 'Minggu', 'Minggu'];
        $armadas = Armada::all();
        $rutes = Rute::all();
        $petugas = Petugas::all();

        if ($armadas->count() < 1 || $rutes->count() < 1 || $petugas->count() < 2) {
            $this->command->warn('Seeder dibatalkan: butuh minimal 1 armada, 1 rute, dan 2 petugas');
            return;
        }

        foreach ($days as $index => $day) {
            // Ambil armada dan rute satu per satu secara berulang
            $armada = $armadas[$index % $armadas->count()];
            $rute = $rutes[$index % $rutes->count()];

            $template = JadwalTemplate::create([
                'hari' => $day,
                'id_armada' => $armada->id,
                'id_rute' => $rute->id
            ]);

            // Tambahkan dua petugas: satu driver, satu picker
            JadwalTemplatePetugas::create([
                'jadwal_template_id' => $template->id,
                'id_petugas' => $petugas[0]->id,
                'tugas' => 1, // Driver
            ]);

            JadwalTemplatePetugas::create([
                'jadwal_template_id' => $template->id,
                'id_petugas' => $petugas[1]->id,
                'tugas' => 2, // Picker
            ]);
        }

        $this->command->info('JadwalTemplateSeeder selesai!');
    }
}
