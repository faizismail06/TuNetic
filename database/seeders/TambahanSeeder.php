<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Rute;
use Illuminate\Database\Seeder;

class TambahanSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ArmadaSeeder::class);
        $this->call(RuteSeeder::class);
        $this->call(RuteTpsSeeder::class);
        $this->call(JadwalSeeder::class);
        // $this->call(JadwalOperasionalSeeder::class);
        // $this->call(UserSeeder::class);
        $this->call(PetugasSeeder::class);
        // $this->call(PenugasanPetugasSeeder::class);
        $this->call(JadwalTemplateSeeder::class);
    }
}
