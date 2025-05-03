<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PetugasMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ==================== MENU PETUGAS ====================
        $menuPetugas = Menu::create([
            'nama_menu' => 'Menu Petugas',
            'url' => '#',
            'icon' => '',
            'parent_id' => '0',
            'urutan' => 3
        ]);

        // Dashboard Petugas
        $dashboardPetugas = Menu::create([
            'nama_menu' => 'Home',
            'url' => 'petugas/home',
            'icon' => '',
            'parent_id' => $menuPetugas->id,
            'urutan' => 1
        ]);

        Permission::create(['name' => 'access_petugas_dashboard', 'menu_id' => $dashboardPetugas->id]);

        // Jadwal Pengambilan
        $jadwalPengambilan = Menu::create([
            'nama_menu' => 'Jadwal Pengambilan',
            'url' => '/jadwal-pengambilan',
            'icon' => '',
            'parent_id' => $menuPetugas->id,
            'urutan' => 2
        ]);

        Permission::create(['name' => 'read_jadwal_pengambilan', 'menu_id' => $jadwalPengambilan->id]);

        // Lapor Sampah
        $laporSampah = Menu::create([
            'nama_menu' => 'Lapor Sampah',
            'url' => 'petugas/lapor',
            'icon' => '',
            'parent_id' => $menuPetugas->id,
            'urutan' => 3
        ]);

        Permission::create(['name' => 'create_laporan_sampah', 'menu_id' => $laporSampah->id]);
        Permission::create(['name' => 'read_laporan_sampah', 'menu_id' => $laporSampah->id]);
        Permission::create(['name' => 'update_laporan_sampah', 'menu_id' => $laporSampah->id]);

        // Profile Petugas
        $profilePetugas = Menu::create([
            'nama_menu' => 'Profile',
            'url' => 'petugas/profile',
            'icon' => '',
            'parent_id' => $menuPetugas->id,
            'urutan' => 4
        ]);

        Permission::create(['name' => 'access_petugas_profile', 'menu_id' => $profilePetugas->id]);
        Permission::create(['name' => 'update_petugas_profile', 'menu_id' => $profilePetugas->id]);

        $akun = Menu::create([
            'nama_menu' => 'Akun',
            'url' => 'manage-user',
            'parent_id' => $profilePetugas->id,
            'urutan' => 1
        ]);

        Permission::create(['name' => 'create_user', 'menu_id' => $akun->id]);
        Permission::create(['name' => 'read_user', 'menu_id' => $akun->id]);
        Permission::create(['name' => 'update_user', 'menu_id' => $akun->id]);
        Permission::create(['name' => 'delete_user', 'menu_id' => $akun->id]);

        $keluar = Menu::create([
            'nama_menu' => 'Keluar',
            'url' => 'manage-user',
            'parent_id' => $profilePetugas->id,
            'urutan' => 1
        ]);

        // Return the main menu id to be used by MasterSeeder
        return $menuPetugas->id;
    }
}
