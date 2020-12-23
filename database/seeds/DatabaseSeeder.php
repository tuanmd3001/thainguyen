<?php

use App\Models\Admin\AdminUser;
use App\Models\Constants;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $admin = AdminUser::where('name', 'admin')->first();
        if (!$admin){
            $admin = AdminUser::create([
                'name' => 'admin',
                'username' => 'admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make(Constants::DEFAULT_PASSWORD),
            ]);
        }
        $super_admin = Role::where('guard_name', 'admins')->where('name', 'SuperAdmin')->first();
        if(!$super_admin){
            Role::create([
               'name' => 'SuperAdmin',
               'guard_name' => 'admins'
            ]);
        }
        $admin->assignRole('SuperAdmin');

        $adminUsers = Permission::where('guard_name', 'admins')->where('name', 'AdminUsers')->first();
        $adminRoles = Permission::where('guard_name', 'admins')->where('name', 'AdminRoles')->first();
        $adminPermissions = Permission::where('guard_name', 'admins')->where('name', 'AdminPermissions')->first();
        if (!$adminUsers){
            $adminUsers = Permission::create([
                'name' => 'AdminUsers',
                'display_name' => 'Quản lý quản trị viên',
                'guard_name' => 'admins'
            ]);
        }

        if (!$adminRoles){
            $adminRoles = Permission::create([
                'name' => 'AdminRoles',
                'display_name' => 'Quản lý nhóm quản trị viên',
                'guard_name' => 'admins'
            ]);
        }

        if (!$adminPermissions){
            $adminPermissions = Permission::create([
                'name' => 'AdminPermissions',
                'display_name' => 'Quản lý quyền quản trị viên',
                'guard_name' => 'admins'
            ]);
        }


        $users = Permission::where('guard_name', 'admins')->where('name', 'Users')->first();
        $roles = Permission::where('guard_name', 'admins')->where('name', 'Roles')->first();
        $permissions = Permission::where('guard_name', 'admins')->where('name', 'Permissions')->first();
        if (!$users){
            $users = Permission::create([
                'name' => 'Users',
                'display_name' => 'Quản lý người đọc',
                'guard_name' => 'admins'
            ]);
        }
        if (!$roles){
            $roles = Permission::create([
                'name' => 'Roles',
                'display_name' => 'Quản lý nhóm người đọc',
                'guard_name' => 'admins'
            ]);
        }
        if (!$permissions){
            $permissions = Permission::create([
                'name' => 'Permissions',
                'display_name' => 'Quản lý quyền người đọc',
                'guard_name' => 'admins'
            ]);
        }
        $config = Permission::where('guard_name', 'admins')->where('name', 'Config')->first();
        if (!$config){
            $users = Permission::create([
                'name' => 'Config',
                'display_name' => 'Cài đặt hệ thống',
                'guard_name' => 'admins'
            ]);
        }

        // $this->call(UsersTableSeeder::class);
    }
}
