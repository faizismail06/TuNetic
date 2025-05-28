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
        // Call each menu seeder and collect main menu IDs
        $adminPusatMenuId = $this->call(AdminPusatMenuSeeder::class);
        $adminTpstMenuId = $this->call(AdminTpstMenuSeeder::class);
        $petugasMenuId = $this->call(PetugasMenuSeeder::class);
        $userMenuId = $this->call(UserMenuSeeder::class);

        // ==================== ROLE MANAGEMENT ====================

        // Get menu IDs for each role
        $adminPusatMenuIds = $this->getMenuIdsForRole('admin_pusat');
        $adminTpstMenuIds = $this->getMenuIdsForRole('admin_tpst');
        $petugasMenuIds = $this->getMenuIdsForRole('petugas');
        $userMenuIds = $this->getMenuIdsForRole('user');

        // Create roles with levels
        $adminPusatRole = Role::create(['name' => 'admin_pusat', 'level' => 1]);
        $adminTpstRole = Role::create(['name' => 'admin_tpst', 'level' => 2]);
        $petugasRole = Role::create(['name' => 'petugas', 'level' => 3]);
        $userRole = Role::create(['name' => 'user', 'level' => 4]);

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
        $this->assignMenusToRole($adminPusatMenuIds, $adminPusatRole->id);
        $this->assignMenusToRole($adminTpstMenuIds, $adminTpstRole->id);
        $this->assignMenusToRole($petugasMenuIds, $petugasRole->id);
        $this->assignMenusToRole($userMenuIds, $userRole->id);

        // Create sample users for each role
        $adminPusat = User::create([
            'name' => 'Admin Pusat',
            'email' => 'admin.pusat@tunetic.com',
            'password' => Hash::make('adminadmin'),
            'level' => 1,
        ]);
        $adminPusat->assignRole('admin_pusat');

        $adminTpst = User::create([
            'name' => 'Admin TPST',
            'email' => 'admin.tpst@tunetic.com',
            'password' => Hash::make('admintpst'),
            'level' => 2,
        ]);
        $adminTpst->assignRole('admin_tpst');

        $petugas = User::create([
            'name' => 'Petugas Sampah',
            'email' => 'petugas@tunetic.com',
            'password' => Hash::make('petugaspetugas'),
            'level' => 3,
        ]);
        $petugas->assignRole('petugas');

        $petugas = User::create([
            'name' => 'Petugas Sampah 2',
            'email' => 'petugas2@tunetic.com',
            'password' => Hash::make('petugaspetugas'),
            'alamat' => 'Jl. Kebersihan No. 67',
            'level' => 3,
        ]);
        $petugas->assignRole('petugas');

        $user = User::create([
            'name' => 'User Biasa',
            'email' => 'user@tunetic.com',
            'password' => Hash::make('useruser'),
            'level' => 4,
        ]);
        $user->assignRole('user');
    }

    /**
     * Get menu IDs for a specific role
     *
     * @param string $roleName
     * @return \Illuminate\Support\Collection
     */
    private function getMenuIdsForRole($roleName)
    {
        switch ($roleName) {
            case 'admin_pusat':
                // Get all menus for Admin Pusat
                $mainMenu = Menu::where('nama_menu', 'Menu Admin Pusat')->first();
                if (!$mainMenu) return collect();

                return Menu::where(function ($query) use ($mainMenu) {
                    $query->where('id', $mainMenu->id)
                        ->orWhere('parent_id', $mainMenu->id)
                        ->orWhereIn('parent_id', Menu::where('parent_id', $mainMenu->id)->pluck('id'));
                })->pluck('id');

            case 'admin_tpst':
                // Get all menus for Admin TPST
                $mainMenu = Menu::where('nama_menu', 'Menu Admin TPST')->first();
                if (!$mainMenu) return collect();

                return Menu::where(function ($query) use ($mainMenu) {
                    $query->where('id', $mainMenu->id)
                        ->orWhere('parent_id', $mainMenu->id);
                })->pluck('id');

            case 'petugas':
                // Get all menus for Petugas
                $mainMenu = Menu::where('nama_menu', 'Menu Petugas')->first();
                if (!$mainMenu) return collect();

                return Menu::where(function ($query) use ($mainMenu) {
                    $query->where('id', $mainMenu->id)
                        ->orWhere('parent_id', $mainMenu->id);
                })->pluck('id');

            case 'user':
                // Get all menus for User
                $mainMenu = Menu::where('nama_menu', 'Menu User')->first();
                if (!$mainMenu) return collect();

                return Menu::where(function ($query) use ($mainMenu) {
                    $query->where('id', $mainMenu->id)
                        ->orWhere('parent_id', $mainMenu->id);
                })->pluck('id');

            default:
                return collect();
        }
    }
    private function assignMenusToRole($menuIds, $roleId)
    {
        foreach ($menuIds as $menuId) {
            DB::table('role_has_menus')->insert([
                'menu_id' => $menuId,
                'role_id' => $roleId,
            ]);
        }
    }
}
