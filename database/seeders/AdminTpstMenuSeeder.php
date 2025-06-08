<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class AdminTpstMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ==================== MENU ADMIN TPST ====================
        $menuAdminTpst = Menu::create([
            'nama_menu' => 'Menu Admin TPST',
            'url' => '#',
            'icon' => 'fas fa-recycle',
            'parent_id' => '0',
            'urutan' => 2
        ]);

        // Dashboard Admin TPST
        $dashboardAdminTpst = Menu::create([
            'nama_menu' => 'Dashboard',
            'url' => 'tpst/home',
            'icon' => 'fas fa-home',
            'parent_id' => $menuAdminTpst->id,
            'urutan' => 1
        ]);

        Permission::create(['name' => 'access_tpst_dashboard', 'menu_id' => $dashboardAdminTpst->id]);

        // Jadwal & Rute
        $jadwalRuteTpst = Menu::create([
            'nama_menu' => 'Jadwal & Rute',
            'url' => 'tpst/jadwal-rute',
            'icon' => 'fas fa-route',
            'parent_id' => $menuAdminTpst->id,
            'urutan' => 2
        ]);

        Permission::create(['name' => 'read_jadwal_rute_tpst', 'menu_id' => $jadwalRuteTpst->id]);
        Permission::create(['name' => 'update_jadwal_rute_tpst', 'menu_id' => $jadwalRuteTpst->id]);

        // Perhitungan Sampah TPST
        $perhitunganSampahTpst = Menu::create([
            'nama_menu' => 'Perhitungan Sampah',
            'url' => 'tpst/perhitungan-sampah',
            'icon' => 'fas fa-calculator',
            'parent_id' => $menuAdminTpst->id,
            'urutan' => 3
        ]);

        Permission::create(['name' => 'create_perhitungan_tpst', 'menu_id' => $perhitunganSampahTpst->id]);
        Permission::create(['name' => 'read_perhitungan_tpst', 'menu_id' => $perhitunganSampahTpst->id]);
        Permission::create(['name' => 'update_perhitungan_tpst', 'menu_id' => $perhitunganSampahTpst->id]);
        Permission::create(['name' => 'delete_perhitungan_tpst', 'menu_id' => $perhitunganSampahTpst->id]);

        // Return the main menu id to be used by MasterSeeder
        return $menuAdminTpst->id;
    }
}
