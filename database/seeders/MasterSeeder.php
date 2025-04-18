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
        // ==================== MENU ADMIN PUSAT ====================
        $menuAdminPusat = Menu::create([
            'nama_menu' => 'Menu Admin Pusat',
            'url' => '#',
            'icon' => 'fas fa-cogs',
            'parent_id' => '0',
            'urutan' => 1
        ]);

        // Dashboard Admin Pusat
        $dashboardAdmin = Menu::create([
            'nama_menu' => 'Dashboard',
            'url' => 'admin/home',
            'icon' => 'fas fa-home',
            'parent_id' => $menuAdminPusat->id,
            'urutan' => 1
        ]);

        Permission::create(['name' => 'access_dashboard', 'menu_id' => $dashboardAdmin->id]);

        // Manajemen Data Menu
        $manajemenData = Menu::create([
            'nama_menu' => 'Manajemen Data',
            'url' => '#',
            'icon' => 'fas fa-users-cog',
            'parent_id' => $menuAdminPusat->id,
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
            'parent_id' => $menuAdminPusat->id,
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
            'parent_id' => $menuAdminPusat->id,
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
            'parent_id' => $menuAdminPusat->id,
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

        // Petugas Armada (New)
        // $petugasArmada = Menu::create([
        //     'nama_menu' => 'Petugas Armada',
        //     'url' => 'petugas-armada',
        //     'parent_id' => $jadwalArmada->id,
        //     'urutan' => 3
        // ]);

        // Permission::create(['name' => 'create_petugas_armada', 'menu_id' => $petugasArmada->id]);
        // Permission::create(['name' => 'read_petugas_armada', 'menu_id' => $petugasArmada->id]);
        // Permission::create(['name' => 'update_petugas_armada', 'menu_id' => $petugasArmada->id]);
        // Permission::create(['name' => 'delete_petugas_armada', 'menu_id' => $petugasArmada->id]);

        // Perhitungan Sampah
        $perhitunganSampah = Menu::create([
            'nama_menu' => 'Perhitungan Sampah',
            'url' => 'perhitungan-sampah',
            'icon' => 'fas fa-calculator',
            'parent_id' => $menuAdminPusat->id,
            'urutan' => 6
        ]);

        Permission::create(['name' => 'create_perhitungan', 'menu_id' => $perhitunganSampah->id]);
        Permission::create(['name' => 'read_perhitungan', 'menu_id' => $perhitunganSampah->id]);
        Permission::create(['name' => 'update_perhitungan', 'menu_id' => $perhitunganSampah->id]);
        Permission::create(['name' => 'delete_perhitungan', 'menu_id' => $perhitunganSampah->id]);

        // Profile
        $profileAdmin = Menu::create([
            'nama_menu' => 'Profile',
            'url' => 'admin/profile',
            'icon' => 'fas fa-user',
            'parent_id' => $menuAdminPusat->id,
            'urutan' => 7
        ]);

        Permission::create(['name' => 'access_profile', 'menu_id' => $profileAdmin->id]);
        Permission::create(['name' => 'update_profile', 'menu_id' => $profileAdmin->id]);

        // Backup Server Menu (Admin Only)
        $backupServer = Menu::create([
            'nama_menu' => 'Backup Server',
            'url' => '#',
            'icon' => 'fas fa-server',
            'parent_id' => $menuAdminPusat->id,
            'urutan' => 8
        ]);

        $backupDatabase = Menu::create([
            'nama_menu' => 'Backup Database',
            'url' => 'dbbackup',
            'icon' => 'fas fa-database',
            'parent_id' => $backupServer->id,
            'urutan' => 1
        ]);

        Permission::create(['name' => 'backup_database', 'menu_id' => $backupDatabase->id]);

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

        // Profile Admin TPST
        $profileAdminTpst = Menu::create([
            'nama_menu' => 'Profile',
            'url' => 'tpst/profile',
            'icon' => 'fas fa-user',
            'parent_id' => $menuAdminTpst->id,
            'urutan' => 4
        ]);

        Permission::create(['name' => 'access_tpst_profile', 'menu_id' => $profileAdminTpst->id]);
        Permission::create(['name' => 'update_tpst_profile', 'menu_id' => $profileAdminTpst->id]);

        // ==================== MENU PETUGAS ====================
        $menuPetugas = Menu::create([
            'nama_menu' => 'Menu Petugas',
            'url' => '#',
            'icon' => 'fas fa-user-hard-hat',
            'parent_id' => '0',
            'urutan' => 3
        ]);

        // Dashboard Petugas
        $dashboardPetugas = Menu::create([
            'nama_menu' => 'Home',
            'url' => 'petugas/home',
            'icon' => 'fas fa-home',
            'parent_id' => $menuPetugas->id,
            'urutan' => 1
        ]);

        Permission::create(['name' => 'access_petugas_dashboard', 'menu_id' => $dashboardPetugas->id]);

        // Jadwal Pengambilan
        $jadwalPengambilan = Menu::create([
            'nama_menu' => 'Jadwal Pengambilan',
            'url' => 'petugas/jadwal',
            'icon' => 'fas fa-calendar-alt',
            'parent_id' => $menuPetugas->id,
            'urutan' => 2
        ]);

        Permission::create(['name' => 'read_jadwal_pengambilan', 'menu_id' => $jadwalPengambilan->id]);

        // Lapor Sampah
        $laporSampah = Menu::create([
            'nama_menu' => 'Lapor Sampah',
            'url' => 'petugas/lapor',
            'icon' => 'fas fa-clipboard-list',
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
            'icon' => 'fas fa-user',
            'parent_id' => $menuPetugas->id,
            'urutan' => 4
        ]);

        Permission::create(['name' => 'access_petugas_profile', 'menu_id' => $profilePetugas->id]);
        Permission::create(['name' => 'update_petugas_profile', 'menu_id' => $profilePetugas->id]);

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

        // ==================== ROLE MANAGEMENT ====================

        // Get menu IDs for each role
        $adminPusatMenuIds = Menu::where(function ($query) use ($menuAdminPusat, $manajemenData, $jadwalArmada, $backupServer) {
            $query->where('id', $menuAdminPusat->id)
                ->orWhere('parent_id', $menuAdminPusat->id)
                ->orWhereIn('parent_id', [
                    $manajemenData->id,
                    $jadwalArmada->id,
                    $backupServer->id
                ]);
        })->pluck('id');

        $adminTpstMenuIds = Menu::where(function ($query) use ($menuAdminTpst) {
            $query->where('id', $menuAdminTpst->id)
                ->orWhere('parent_id', $menuAdminTpst->id);
        })->pluck('id');

        $petugasMenuIds = Menu::where(function ($query) use ($menuPetugas) {
            $query->where('id', $menuPetugas->id)
                ->orWhere('parent_id', $menuPetugas->id);
        })->pluck('id');

        $userMenuIds = Menu::where(function ($query) use ($menuUser) {
            $query->where('id', $menuUser->id)
                ->orWhere('parent_id', $menuUser->id);
        })->pluck('id');

        // Create roles with levels
        $adminPusatRole = Role::create(['name' => 'admin_pusat', 'level' => 1]);
        $adminTpstRole = Role::create(['name' => 'admin_tpst', 'level' => 2]);
        $petugasRole = Role::create(['name' => 'petugas', 'level' => 3]);
        $userRole = Role::create(['name' => 'user', 'level' => 4]);

        // Get all permissions
        $allPermissions = Permission::all();

        // Get permissions for each role
        $adminPusatPermissions = Permission::whereNotIn('name', [
            'access_tpst_dashboard', 'read_jadwal_rute_tpst', 'update_jadwal_rute_tpst',
            'create_perhitungan_tpst', 'read_perhitungan_tpst', 'update_perhitungan_tpst', 'delete_perhitungan_tpst',
            'access_tpst_profile', 'update_tpst_profile',
            'access_petugas_dashboard', 'read_jadwal_pengambilan', 'create_laporan_sampah',
            'read_laporan_sampah', 'update_laporan_sampah', 'access_petugas_profile', 'update_petugas_profile',
            'access_user_home', 'create_laporan_sampah_user', 'read_laporan_sampah_user',
            'view_rute_armada', 'access_user_profile', 'update_user_profile'
        ])->get();

        $adminTpstPermissions = Permission::whereIn('name', [
            'access_tpst_dashboard', 'read_jadwal_rute_tpst', 'update_jadwal_rute_tpst',
            'create_perhitungan_tpst', 'read_perhitungan_tpst', 'update_perhitungan_tpst', 'delete_perhitungan_tpst',
            'access_tpst_profile', 'update_tpst_profile'
        ])->get();

        $petugasPermissions = Permission::whereIn('name', [
            'access_petugas_dashboard', 'read_jadwal_pengambilan', 'create_laporan_sampah',
            'read_laporan_sampah', 'update_laporan_sampah', 'access_petugas_profile', 'update_petugas_profile'
        ])->get();

        $userPermissions = Permission::whereIn('name', [
            'access_user_home', 'create_laporan_sampah_user', 'read_laporan_sampah_user',
            'view_rute_armada', 'access_user_profile', 'update_user_profile'
        ])->get();

        // Assign permissions to roles
        $adminPusatRole->givePermissionTo($adminPusatPermissions);
        $adminTpstRole->givePermissionTo($adminTpstPermissions);
        $petugasRole->givePermissionTo($petugasPermissions);
        $userRole->givePermissionTo($userPermissions);

        // Assign menus to roles
        foreach ($adminPusatMenuIds as $menuId) {
            DB::table('role_has_menus')->insert([
                'menu_id' => $menuId,
                'role_id' => $adminPusatRole->id
            ]);
        }

        foreach ($adminTpstMenuIds as $menuId) {
            DB::table('role_has_menus')->insert([
                'menu_id' => $menuId,
                'role_id' => $adminTpstRole->id
            ]);
        }

        foreach ($petugasMenuIds as $menuId) {
            DB::table('role_has_menus')->insert([
                'menu_id' => $menuId,
                'role_id' => $petugasRole->id
            ]);
        }

        foreach ($userMenuIds as $menuId) {
            DB::table('role_has_menus')->insert([
                'menu_id' => $menuId,
                'role_id' => $userRole->id
            ]);
        }

        // Create sample users for each role
        $adminPusat = User::create([
            'name' => 'Admin Pusat',
            'email' => 'admin.pusat@example.com',
            'password' => Hash::make('adminadmin'),
            'alamat' => 'Jl. Pusat No. 123',
            'level' => 1,
        ]);
        $adminPusat->assignRole('admin_pusat');

        $adminTpst = User::create([
            'name' => 'Admin TPST',
            'email' => 'admin.tpst@example.com',
            'password' => Hash::make('admintpst'),
            'alamat' => 'Jl. TPST No. 45',
            'level' => 2,
        ]);
        $adminTpst->assignRole('admin_tpst');

        $petugas = User::create([
            'name' => 'Petugas Sampah',
            'email' => 'petugas@example.com',
            'password' => Hash::make('petugaspetugas'),
            'alamat' => 'Jl. Kebersihan No. 67',
            'level' => 3,
        ]);
        $petugas->assignRole('petugas');

        $user = User::create([
            'name' => 'User Biasa',
            'email' => 'user@example.com',
            'password' => Hash::make('useruser'),
            'alamat' => 'Jl. Warga No. 89',
            'level' => 4,
        ]);
        $user->assignRole('user');
    }
}
