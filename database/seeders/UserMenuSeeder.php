<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class UserMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ==================== MENU USER BIASA/MASYARAKAT ====================
        $menuUser = Menu::create([
            'nama_menu' => 'Menu User',
            'url' => '#',
            'icon' => '',
            'parent_id' => '0',
            'urutan' => 4
        ]);

        // Home User
        $homeUser = Menu::create([
            'nama_menu' => 'Home',
            'url' => 'masyarakat',
            'icon' => '',
            'parent_id' => $menuUser->id,
            'urutan' => 1
        ]);

        Permission::create(['name' => 'access_user_home', 'menu_id' => $homeUser->id]);

        // Laporan Sampah
        $laporanSampahUser = Menu::create([
            'nama_menu' => 'Laporan Sampah',
            'url' => 'masyarakat/lapor',
            'icon' => '',
            'parent_id' => $menuUser->id,
            'urutan' => 2
        ]);

        Permission::create(['name' => 'create_laporan_sampah_user', 'menu_id' => $laporanSampahUser->id]);
        Permission::create(['name' => 'read_laporan_sampah_user', 'menu_id' => $laporanSampahUser->id]);

        // Rute Armada
        $ruteArmadaUser = Menu::create([
            'nama_menu' => 'Rute Armada',
            'url' => 'masyarakat/rute-armada',
            'icon' => '',
            'parent_id' => $menuUser->id,
            'urutan' => 3
        ]);

        Permission::create(['name' => 'view_rute_armada', 'menu_id' => $ruteArmadaUser->id]);

        // Profile User
        $profileUser = Menu::create([
            'nama_menu' => 'Profile',
            'url' => 'masyarakat/profile',
            'icon' => '',
            'parent_id' => $menuUser->id,
            'urutan' => 4
        ]);

        Permission::create(['name' => 'access_user_profile', 'menu_id' => $profileUser->id]);
        Permission::create(['name' => 'update_user_profile', 'menu_id' => $profileUser->id]);

        // Return the main menu id to be used by MasterSeeder
        return $menuUser->id;
    }
}
