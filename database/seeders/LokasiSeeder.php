<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LokasiSeeder extends Seeder
{
    public function run()
    {
        DB::table('lokasi_tps')->insert([
            [
                'id' => 1,
                'nama_lokasi' => 'TPS Tembalang',
                'province_id' => 33,
                'regency_id' => 3322,
                'district_id' => 337410,
                'village_id' => 3374101006,
                'latitude' => '-6.200000',
                'longitude' => '106.816666',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 2,
                'nama_lokasi' => 'TPS Banyumanik',
                'province_id' => 33,
                'regency_id' => 3322,
                'district_id' => 337410,
                'village_id' => 3374101006,
                'latitude' => '-6.200000',
                'longitude' => '106.816666',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // Tambahkan data lainnya sesuai kebutuhan
        ]);
    }
}
