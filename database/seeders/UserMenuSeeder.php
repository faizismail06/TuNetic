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
            'icon' => 'fas fa-users',
            'parent_id' => '0',
            'urutan' => 4
        ]);

        // Home User
        $homeUser = Menu::create([
            'nama_menu' => 'Home',
            'url' => 'user/home',
            'icon' => 'fas fa-home',
            'parent_id' => $menuUser->id,
            'urutan' => 1
        ]);

        Permission::create(['name' => 'access_user_home', 'menu_id' => $homeUser->id]);

        // Laporan Sampah
        $laporanSampahUser = Menu::create([
            'nama_menu' => 'Laporan Sampah',
            'url' => 'user/laporan-sampah',
            'icon' => 'fas fa-trash',
            'parent_id' => $menuUser->id,
            'urutan' => 2
        ]);

        Permission::create(['name' => 'create_laporan_sampah_user', 'menu_id' => $laporanSampahUser->id]);
        Permission::create(['name' => 'read_laporan_sampah_user', 'menu_id' => $laporanSampahUser->id]);

        // Rute Armada
        $ruteArmadaUser = Menu::create([
            'nama_menu' => 'Rute Armada',
            'url' => 'user/rute-armada',
            'icon' => 'fas fa-route',
            'parent_id' => $menuUser->id,
            'urutan' => 3
        ]);

        Permission::create(['name' => 'view_rute_armada', 'menu_id' => $ruteArmadaUser->id]);

        // Profile User
        $profileUser = Menu::create([
            'nama_menu' => 'Profile',
            'url' => 'user/profile',
            'icon' => 'fas fa-user',
            'parent_id' => $menuUser->id,
            'urutan' => 4
        ]);

        Permission::create(['name' => 'access_user_profile', 'menu_id' => $profileUser->id]);
        Permission::create(['name' => 'update_user_profile', 'menu_id' => $profileUser->id]);

        $akun = Menu::create([
            'nama_menu' => 'Akun',
            'url' => 'user/profile/akun',
            'icon' => 'fa-solid fa-key',
            'parent_id' => $profileUser->id,
            'urutan' => 1
        ]);

        Permission::create(['name' => 'access_user_profile', 'menu_id' => $akun->id]);
        Permission::create(['name' => 'update_user_profile', 'menu_id' => $akun->id]);

        $jadiPetugas = Menu::create([
            'nama_menu' => 'Jadi Petugas',
            'url' => 'user/profile/akun',
            'icon' => 'fa-solid fa-repeat',
            'parent_id' => $profileUser->id,
            'urutan' => 1
        ]);

        Permission::create(['name' => 'access_user_profile', 'menu_id' => $jadiPetugas->id]);
        Permission::create(['name' => 'update_user_profile', 'menu_id' => $jadiPetugas->id]);

        // Return the main menu id to be used by MasterSeeder
        return $menuUser->id;
    }
}
