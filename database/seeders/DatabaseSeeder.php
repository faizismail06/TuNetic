<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // $this->call(AdminPusatMenuSeeder::class);
        // $this->call(AdminTpstMenuSeeder::class);
        // $this->call(PetugasMenuSeeder::class);
        // $this->call(UserMenuSeeder::class);
        $this->call(MasterSeeder::class);
        $this->call(ProvincesTableSeeder::class);
        $this->call(RegenciesTableSeeder::class);
        $this->call(DistrictsTableSeeder::class);
        $this->call(VillagesTableSeeder::class);
        $this->call(LokasiTpsSeeder::class);
        $this->call(TambahanSeeder::class);
        // $this->call(UserSeeder::class);
    }
}
