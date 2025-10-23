<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Role::truncate();
        $role_admin = Role::create(['name' => User::ADMIN, 'guard_name' => 'web']);
        $role_owner = Role::create(['name' => User::OWNER, 'guard_name' => 'web']);
        $role_cashier = Role::create(['name' => User::CASHIER, 'guard_name' => 'web']);
        $role_inventoryStaff = Role::create(['name' => User::INVENTORY_STAFF, 'guard_name' => 'web']);

        $permission_admin = [
            'create_user_management',
            'read_user_management',
            'update_user_management',
            'delete_user_management',
            'resetPassword_user_management',

            'create_role',
            'read_role',
            'update_role',
            'delete_role',

            'create_permission',
            'read_permission',
            'update_permission',
            'delete_permission',
        ];


        $permission_owner = [];
        $permission_cashier = [];
        $permission_inventoryStaff = [];


        $role_admin->givePermissionTo($permission_admin);
        $role_owner->givePermissionTo($permission_owner);
        $role_cashier->givePermissionTo($permission_cashier);
        $role_inventoryStaff->givePermissionTo($permission_inventoryStaff);



        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
