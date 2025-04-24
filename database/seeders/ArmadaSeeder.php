<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Armada;

class ArmadaSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['jenis_kendaraan' => 'Truk',  'merk_kendaraan' => 'Hino',  'no_polisi' => 'H 1234 AB', 'kapasitas' => 3000],
            ['jenis_kendaraan' => 'Pickup','merk_kendaraan' => 'Toyota','no_polisi' => 'H 4567 US', 'kapasitas' => 1500],
            ['jenis_kendaraan' => 'Truk',  'merk_kendaraan' => 'Isuzu', 'no_polisi' => 'H 5681 AH', 'kapasitas' => 3500],
            ['jenis_kendaraan' => 'Pickup','merk_kendaraan' => 'Suzuki','no_polisi' => 'H 9981 BA', 'kapasitas' => 1200],
            ['jenis_kendaraan' => 'Truk',  'merk_kendaraan' => 'Mitsubishi','no_polisi' => 'H 6674 SU', 'kapasitas' => 4000],
        ];

        foreach ($data as $item) {
            Armada::create($item);
        }

        $this->command->info('Seeder Armada berhasil dijalankan.');
    }
}
