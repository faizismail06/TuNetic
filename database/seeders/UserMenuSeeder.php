<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class UserMenuSeeder extends Seeder
{
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
        Permission::firstOrCreate(['name' => 'access_user_home', 'guard_name' => 'web'], ['menu_id' => $homeUser->id]);

        // Laporan Sampah
        $laporanSampahUser = Menu::create([
            'nama_menu' => 'Laporan Sampah',
            'url' => 'masyarakat/lapor',
            'icon' => '',
            'parent_id' => $menuUser->id,
            'urutan' => 2
        ]);
        Permission::firstOrCreate(['name' => 'create_laporan_sampah_user', 'guard_name' => 'web'], ['menu_id' => $laporanSampahUser->id]);
        Permission::firstOrCreate(['name' => 'read_laporan_sampah_user', 'guard_name' => 'web'], ['menu_id' => $laporanSampahUser->id]);

        // Rute Armada
        $ruteArmadaUser = Menu::create([
            'nama_menu' => 'Rute Armada',
            'url' => 'masyarakat/rute-armada',
            'icon' => '',
            'parent_id' => $menuUser->id,
            'urutan' => 3
        ]);
        Permission::firstOrCreate(['name' => 'view_rute_armada', 'guard_name' => 'web'], ['menu_id' => $ruteArmadaUser->id]);

        // Profile User
        $profileUser = Menu::create([
            'nama_menu' => 'Profile',
            'url' => 'masyarakat/profile',
            'icon' => '',
            'parent_id' => $menuUser->id,
            'urutan' => 4
        ]);
        Permission::firstOrCreate(['name' => 'access_user_profile', 'guard_name' => 'web'], ['menu_id' => $profileUser->id]);
        Permission::firstOrCreate(['name' => 'update_user_profile', 'guard_name' => 'web'], ['menu_id' => $profileUser->id]);

        // Akun Submenu
        $akun = Menu::create([
            'nama_menu' => 'Akun',
            'url' => 'user/profile/akun',
            'icon' => 'fa-solid fa-key',
            'parent_id' => $profileUser->id,
            'urutan' => 1
        ]);
        Permission::firstOrCreate(['name' => 'access_user_profile_akun', 'guard_name' => 'web'], ['menu_id' => $akun->id]);
        Permission::firstOrCreate(['name' => 'update_user_profile_akun', 'guard_name' => 'web'], ['menu_id' => $akun->id]);

        // Jadi Petugas Submenu
        $jadiPetugas = Menu::create([
            'nama_menu' => 'Jadi Petugas',
            'url' => 'user/profile/akun',
            'icon' => 'fa-solid fa-repeat',
            'parent_id' => $profileUser->id,
            'urutan' => 2
        ]);
        Permission::firstOrCreate(['name' => 'access_user_profile_petugas', 'guard_name' => 'web'], ['menu_id' => $jadiPetugas->id]);
        Permission::firstOrCreate(['name' => 'update_user_profile_petugas', 'guard_name' => 'web'], ['menu_id' => $jadiPetugas->id]);

        return $menuUser->id;
    }
}
