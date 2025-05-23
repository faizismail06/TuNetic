<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rute;

class RuteSeeder extends Seeder
{
    public function run(): void
    {
        $rutes = [
            [
                'nama_rute' => 'Timur-1',
                'latitude' => -7.015,
                'longitude' => 110.435,
                'wilayah' => 'Timur'
            ],
            [
                'nama_rute' => 'Selatan-1',
                'latitude' => -7.025,
                'longitude' => 110.432,
                'wilayah' => 'Selatan'
            ],
            [
                'nama_rute' => 'Pusat-2',
                'latitude' => -7.011,
                'longitude' => 110.440,
                'wilayah' => 'Pusat'
            ],
            [
                'nama_rute' => 'Barat-2',
                'latitude' => -7.020,
                'longitude' => 110.420,
                'wilayah' => 'Barat'
            ],
            [
                'nama_rute' => 'Utara-1',
                'latitude' => -7.018,
                'longitude' => 110.450,
                'wilayah' => 'Utara'
            ],
        ];

        foreach ($rutes as $rute) {
            Rute::create($rute);
        }

        $this->command->info('Seeder Rute berhasil dijalankan.');
    }
}