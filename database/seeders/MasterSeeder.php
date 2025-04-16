<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Buat Menu Utama
        $menuManajemen = Menu::create([
            'nama_menu' => 'Menu Manajemen',
            'url' => '#',
            'icon' => 'fas fa-cogs',
            'parent_id' => '0',
            'urutan' => 1
        ]);

        // Dashboard Menu
        $dashboardMenu = Menu::create([
            'nama_menu' => 'Dashboard',
            'url' => 'home',
            'icon' => 'fas fa-home',
            'parent_id' => $menuManajemen->id,
            'urutan' => 1
        ]);

        Permission::create(['name' => 'access_dashboard', 'menu_id' => $dashboardMenu->id]);

        // Manajemen Data Menu
        $manajemenData = Menu::create([
            'nama_menu' => 'Manajemen Data',
            'url' => '#',
            'icon' => 'fas fa-users-cog',
            'parent_id' => $menuManajemen->id,
            'urutan' => 2
        ]);

        // Kelola User
        $kelolaUser = Menu::create([
            'nama_menu' => 'Kelola User',
            'url' => 'manage-user',
            'parent_id' => $manajemenData->id,
            'urutan' => 1
        ]);

        Permission::create(['name' => 'create_user', 'menu_id' => $kelolaUser->id]);
        Permission::create(['name' => 'read_user', 'menu_id' => $kelolaUser->id]);
        Permission::create(['name' => 'update_user', 'menu_id' => $kelolaUser->id]);
        Permission::create(['name' => 'delete_user', 'menu_id' => $kelolaUser->id]);

        // Kelola Petugas
        $kelolaPetugas = Menu::create([
            'nama_menu' => 'Kelola Petugas',
            'url' => 'manage-petugas',
            'parent_id' => $manajemenData->id,
            'urutan' => 2
        ]);

        Permission::create(['name' => 'create_petugas', 'menu_id' => $kelolaPetugas->id]);
        Permission::create(['name' => 'read_petugas', 'menu_id' => $kelolaPetugas->id]);
        Permission::create(['name' => 'update_petugas', 'menu_id' => $kelolaPetugas->id]);
        Permission::create(['name' => 'delete_petugas', 'menu_id' => $kelolaPetugas->id]);

        // Kelola Armada
        $kelolaArmada = Menu::create([
            'nama_menu' => 'Kelola Armada',
            'url' => 'manage-armada',
            'parent_id' => $manajemenData->id,
            'urutan' => 3
        ]);

        Permission::create(['name' => 'create_armada', 'menu_id' => $kelolaArmada->id]);
        Permission::create(['name' => 'read_armada', 'menu_id' => $kelolaArmada->id]);
        Permission::create(['name' => 'update_armada', 'menu_id' => $kelolaArmada->id]);
        Permission::create(['name' => 'delete_armada', 'menu_id' => $kelolaArmada->id]);

        // Kelola Rute
        $kelolaRute = Menu::create([
            'nama_menu' => 'Kelola Rute',
            'url' => 'manage-rute',
            'parent_id' => $manajemenData->id,
            'urutan' => 4
        ]);

        Permission::create(['name' => 'create_rute', 'menu_id' => $kelolaRute->id]);
        Permission::create(['name' => 'read_rute', 'menu_id' => $kelolaRute->id]);
        Permission::create(['name' => 'update_rute', 'menu_id' => $kelolaRute->id]);
        Permission::create(['name' => 'delete_rute', 'menu_id' => $kelolaRute->id]);

        // Kelola TPS/TPST
        $kelolaTps = Menu::create([
            'nama_menu' => 'Kelola Tps/Tpst',
            'url' => 'lokasi-tps',
            'parent_id' => $manajemenData->id,
            'urutan' => 5
        ]);

        Permission::create(['name' => 'create_tps', 'menu_id' => $kelolaTps->id]);
        Permission::create(['name' => 'read_tps', 'menu_id' => $kelolaTps->id]);
        Permission::create(['name' => 'update_tps', 'menu_id' => $kelolaTps->id]);
        Permission::create(['name' => 'delete_tps', 'menu_id' => $kelolaTps->id]);

        // Laporan Pengaduan
        $laporanPengaduan = Menu::create([
            'nama_menu' => 'Laporan Pengaduan',
            'url' => 'laporan-pengaduan',
            'icon' => 'far fa-comment',
            'parent_id' => $menuManajemen->id,
            'urutan' => 3
        ]);

        Permission::create(['name' => 'read_laporan', 'menu_id' => $laporanPengaduan->id]);
        Permission::create(['name' => 'update_laporan', 'menu_id' => $laporanPengaduan->id]);
        Permission::create(['name' => 'delete_laporan', 'menu_id' => $laporanPengaduan->id]);

        // Artikel
        $artikel = Menu::create([
            'nama_menu' => 'Artikel',
            'url' => 'artikel',
            'icon' => 'fas fa-newspaper',
            'parent_id' => $menuManajemen->id,
            'urutan' => 4
        ]);

        Permission::create(['name' => 'create_artikel', 'menu_id' => $artikel->id]);
        Permission::create(['name' => 'read_artikel', 'menu_id' => $artikel->id]);
        Permission::create(['name' => 'update_artikel', 'menu_id' => $artikel->id]);
        Permission::create(['name' => 'delete_artikel', 'menu_id' => $artikel->id]);

        // Jadwal Armada
        $jadwalArmada = Menu::create([
            'nama_menu' => 'Jadwal Armada',
            'url' => '#',
            'icon' => 'fas fa-truck',
            'parent_id' => $menuManajemen->id,
            'urutan' => 5
        ]);

        // Daftar Jadwal
        $daftarJadwal = Menu::create([
            'nama_menu' => 'Daftar Jadwal',
            'url' => 'daftar-jadwal',
            'parent_id' => $jadwalArmada->id,
            'urutan' => 1
        ]);

        Permission::create(['name' => 'create_jadwal', 'menu_id' => $daftarJadwal->id]);
        Permission::create(['name' => 'read_jadwal', 'menu_id' => $daftarJadwal->id]);
        Permission::create(['name' => 'update_jadwal', 'menu_id' => $daftarJadwal->id]);
        Permission::create(['name' => 'delete_jadwal', 'menu_id' => $daftarJadwal->id]);

        // Jadwal Operasional
        $jadwalOperasional = Menu::create([
            'nama_menu' => 'Jadwal Operasional',
            'url' => 'jadwal-operasional',
            'parent_id' => $jadwalArmada->id,
            'urutan' => 2
        ]);

        Permission::create(['name' => 'create_jadwal_operasional', 'menu_id' => $jadwalOperasional->id]);
        Permission::create(['name' => 'read_jadwal_operasional', 'menu_id' => $jadwalOperasional->id]);
        Permission::create(['name' => 'update_jadwal_operasional', 'menu_id' => $jadwalOperasional->id]);
        Permission::create(['name' => 'delete_jadwal_operasional', 'menu_id' => $jadwalOperasional->id]);

        // Perhitungan Sampah
        $perhitunganSampah = Menu::create([
            'nama_menu' => 'Perhitungan Sampah',
            'url' => 'perhitungan-sampah',
            'icon' => 'fas fa-calculator',
            'parent_id' => $menuManajemen->id,
            'urutan' => 6
        ]);

        Permission::create(['name' => 'create_perhitungan', 'menu_id' => $perhitunganSampah->id]);
        Permission::create(['name' => 'read_perhitungan', 'menu_id' => $perhitunganSampah->id]);
        Permission::create(['name' => 'update_perhitungan', 'menu_id' => $perhitunganSampah->id]);
        Permission::create(['name' => 'delete_perhitungan', 'menu_id' => $perhitunganSampah->id]);

        // Profile
        $profile = Menu::create([
            'nama_menu' => 'Profile',
            'url' => 'profile',
            'icon' => 'fas fa-user',
            'parent_id' => $menuManajemen->id,
            'urutan' => 7
        ]);

        Permission::create(['name' => 'access_profile', 'menu_id' => $profile->id]);
        Permission::create(['name' => 'update_profile', 'menu_id' => $profile->id]);

        // Backup Server Menu (Admin Only)
        $backupServer = Menu::create([
            'nama_menu' => 'Backup Server',
            'url' => '#',
            'icon' => 'fas fa-server',
            'parent_id' => '0',
            'urutan' => 2
        ]);

        $backupDatabase = Menu::create([
            'nama_menu' => 'Backup Database',
            'url' => 'dbbackup',
            'icon' => 'fas fa-database',
            'parent_id' => $backupServer->id,
            'urutan' => 1
        ]);

        Permission::create(['name' => 'backup_database', 'menu_id' => $backupDatabase->id]);

        // Buat petugas menu
        $menuPetugas = Menu::create([
            'nama_menu' => 'Menu Petugas',
            'url' => '#',
            'icon' => 'fas fa-user-hard-hat',
            'parent_id' => '0',
            'urutan' => 3
        ]);

        // Dashboard Petugas
        $dashboardPetugas = Menu::create([
            'nama_menu' => 'Dashboard',
            'url' => 'petugas/home',
            'icon' => 'fas fa-home',
            'parent_id' => $menuPetugas->id,
            'urutan' => 1
        ]);

        Permission::create(['name' => 'access_petugas_dashboard', 'menu_id' => $dashboardPetugas->id]);

        // Jadwal Tugas
        $jadwalTugas = Menu::create([
            'nama_menu' => 'Jadwal Tugas',
            'url' => 'petugas/jadwal',
            'icon' => 'fas fa-calendar-alt',
            'parent_id' => $menuPetugas->id,
            'urutan' => 2
        ]);

        Permission::create(['name' => 'read_jadwal_tugas', 'menu_id' => $jadwalTugas->id]);

        // Laporan Aktivitas
        $laporanAktivitas = Menu::create([
            'nama_menu' => 'Laporan Aktivitas',
            'url' => 'petugas/laporan',
            'icon' => 'fas fa-clipboard-list',
            'parent_id' => $menuPetugas->id,
            'urutan' => 3
        ]);

        Permission::create(['name' => 'create_laporan_aktivitas', 'menu_id' => $laporanAktivitas->id]);
        Permission::create(['name' => 'read_laporan_aktivitas', 'menu_id' => $laporanAktivitas->id]);
        Permission::create(['name' => 'update_laporan_aktivitas', 'menu_id' => $laporanAktivitas->id]);

        // Profile Petugas
        $profilePetugas = Menu::create([
            'nama_menu' => 'Profile',
            'url' => 'petugas/profile',
            'icon' => 'fas fa-user',
            'parent_id' => $menuPetugas->id,
            'urutan' => 4
        ]);

        Permission::create(['name' => 'access_petugas_profile', 'menu_id' => $profilePetugas->id]);
        Permission::create(['name' => 'update_petugas_profile', 'menu_id' => $profilePetugas->id]);

// Setup for role_has_menus - Admin (exclude Menu Petugas)
        $adminMenuIds = Menu::where(function ($query) use ($menuManajemen, $backupServer, $manajemenData, $jadwalArmada) {
            $query->where('parent_id', 0)
                ->where('nama_menu', '!=', 'Menu Petugas') // exclude Menu Petugas
                ->orWhereIn('parent_id', [
                    $menuManajemen->id,
                    $backupServer->id,
                    $manajemenData->id,
                    $jadwalArmada->id
                ]);
        })
        ->pluck('id');


        // Setup for role_has_menus - Petugas
        $petugasMenuIds = Menu::where('parent_id', $menuPetugas->id)->pluck('id');
        $petugasMenuIds[] = $menuPetugas->id;

        // Create admin role with level 1
        $adminRole = Role::create(['name' => 'admin', 'level' => 1]);

        // Create petugas role with level 2
        $petugasRole = Role::create(['name' => 'petugas', 'level' => 3]);

        // Assign all permissions to admin
        $adminPermissions = Permission::all();
        $adminRole->givePermissionTo($adminPermissions);

        // Assign petugas-specific permissions
        $petugasPermissions = Permission::whereIn('name', [
            'access_petugas_dashboard',
            'read_jadwal_tugas',
            'create_laporan_aktivitas',
            'read_laporan_aktivitas',
            'update_laporan_aktivitas',
            'access_petugas_profile',
            'update_petugas_profile'
        ])->get();
        $petugasRole->givePermissionTo($petugasPermissions);

        // Assign menus to roles
        foreach ($adminMenuIds as $menuId) {
            DB::table('role_has_menus')->insert([
                'menu_id' => $menuId,
                'role_id' => $adminRole->id
            ]);
        }

        foreach ($petugasMenuIds as $menuId) {
            DB::table('role_has_menus')->insert([
                'menu_id' => $menuId,
                'role_id' => $petugasRole->id
            ]);
        }

        // Create admin user
        $admin = User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@example.com',
            'password' => Hash::make('adminadmin'),
            'alamat' => 'Jl. Contoh No. 123',
            'level' => 1,
        ]);
        $admin->assignRole('admin');

        // Create petugas user
        $petugas = User::create([
            'name' => 'Petugas Sampah',
            'email' => 'petugas@example.com',
            'password' => Hash::make('petugaspetugas'),
            'alamat' => 'Jl. Kebersihan No. 45',
            'level' => 3,
        ]);
        $petugas->assignRole('petugas');
    }
}
