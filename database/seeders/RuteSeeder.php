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
                'map' => json_encode([
                    ['lat' => -7.015, 'lng' => 110.435],
                    ['lat' => -7.017, 'lng' => 110.439],
                ]),
                'wilayah' => 'Timur'
            ],
            [
                'nama_rute' => 'Selatan-1',
                'map' => json_encode([
                    ['lat' => -7.025, 'lng' => 110.432],
                    ['lat' => -7.027, 'lng' => 110.438],
                ]),
                'wilayah' => 'Selatan'
            ],
            [
                'nama_rute' => 'Pusat-2',
                'map' => json_encode([
                    ['lat' => -7.011, 'lng' => 110.440],
                    ['lat' => -7.013, 'lng' => 110.445],
                ]),
                'wilayah' => 'Pusat'
            ],
            [
                'nama_rute' => 'Barat-2',
                'map' => json_encode([
                    ['lat' => -7.020, 'lng' => 110.420],
                    ['lat' => -7.021, 'lng' => 110.424],
                ]),
                'wilayah' => 'Barat'
            ],
            [
                'nama_rute' => 'Utara-1',
                'map' => json_encode([
                    ['lat' => -7.018, 'lng' => 110.450],
                    ['lat' => -7.019, 'lng' => 110.455],
                ]),
                'wilayah' => 'Utara'
            ],
        ];

        foreach ($rutes as $rute) {
            Rute::create($rute);
        }

        $this->command->info('Seeder Rute berhasil dijalankan.');
    }
}
