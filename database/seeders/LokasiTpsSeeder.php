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
                'nama_lokasi' => 'TPS Tlogosari',
                'province_id' => '33',
                'regency_id' => '3374',
                'district_id' => '3374020',
                'village_id' => '3374020001',
                'latitude' => -7.0051234,
                'longitude' => 110.4501234,
            ],
            [
                'nama_lokasi' => 'TPST Kalipancur',
                'province_id' => '33',
                'regency_id' => '3374',
                'district_id' => '3374010',
                'village_id' => '3374010002',
                'latitude' => -7.0005678,
                'longitude' => 110.4305678,
            ],
            [
                'nama_lokasi' => 'TPS Pedurungan Kidul',
                'province_id' => '33',
                'regency_id' => '3374',
                'district_id' => '3374030',
                'village_id' => '3374030003',
                'latitude' => -6.9954321,
                'longitude' => 110.4604321,
            ],
            [
                'nama_lokasi' => 'TPS Genuk',
                'province_id' => '33',
                'regency_id' => '3374',
                'district_id' => '3374040',
                'village_id' => '3374040004',
                'latitude' => -6.9901234,
                'longitude' => 110.4701234,
            ],
            [
                'nama_lokasi' => 'TPST Banyumanik',
                'province_id' => '33',
                'regency_id' => '3374',
                'district_id' => '3374050',
                'village_id' => '3374050005',
                'latitude' => -6.9856789,
                'longitude' => 110.4806789,
            ],
        ];

        foreach ($data as $item) {
            LokasiTps::create($item);
        }
    }
}
