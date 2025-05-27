<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class AdminPusatMenuSeeder extends Seeder
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
            'url' => 'pusat/home',
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
            'url' => 'pusat/manage-user',
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
            'url' => 'pusat/manage-petugas',
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
            'url' => 'pusat/manage-armada',
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
            'url' => 'pusat/manage-rute',
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
            'url' => 'pusat/lokasi-tps',
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
            'url' => 'pusat/laporan-pengaduan',
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
            'url' => 'pusat/artikel',
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
            'url' => 'pusat/daftar-jadwal',
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
            'url' => 'pusat/jadwal-operasional',
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
            'url' => 'pusat/perhitungan-sampah',
            'icon' => 'fas fa-calculator',
            'parent_id' => $menuAdminPusat->id,
            'urutan' => 6
        ]);

        Permission::create(['name' => 'create_perhitungan', 'menu_id' => $perhitunganSampah->id]);
        Permission::create(['name' => 'read_perhitungan', 'menu_id' => $perhitunganSampah->id]);
        Permission::create(['name' => 'update_perhitungan', 'menu_id' => $perhitunganSampah->id]);
        Permission::create(['name' => 'delete_perhitungan', 'menu_id' => $perhitunganSampah->id]);

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
            'url' => 'pusat/dbbackup',
            'icon' => 'fas fa-database',
            'parent_id' => $backupServer->id,
            'urutan' => 1
        ]);

        Permission::create(['name' => 'backup_database', 'menu_id' => $backupDatabase->id]);

        // Return the main menu id to be used by MasterSeeder
        return $menuAdminPusat->id;
    }
}
