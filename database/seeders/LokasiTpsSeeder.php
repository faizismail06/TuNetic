<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LokasiTps;

class LokasiTpsSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'nama_lokasi' => 'TPS Mangunharjo 01',
                'province_id' => '33', // Jawa Tengah
                'regency_id' => '3374', // Kota Semarang
                'district_id' => '337410', // Tembalang
                'village_id' => '3374101006', // Mangunharjo
                'latitude' => -7.056325,
                'longitude' => 110.454250,
            ],
            [
                'nama_lokasi' => 'TPS Mangunharjo 02',
                'province_id' => '33', // Jawa Tengah
                'regency_id' => '3374', // Kota Semarang
                'district_id' => '337410', // Tembalang
                'village_id' => '3374101006', // Mangunharjo
                'latitude' => -7.055800,
                'longitude' => 110.455900,
            ],
            [
                'nama_lokasi' => 'TPS Mangunharjo 03',
                'province_id' => '33', // Jawa Tengah
                'regency_id' => '3374', // Kota Semarang
                'district_id' => '337410', // Tembalang
                'village_id' => '3374101006', // Mangunharjo
                'latitude' => -7.057100,
                'longitude' => 110.456700,
            ],
        ];

        foreach ($data as $item) {
            LokasiTps::create($item);
        }
    }
}
