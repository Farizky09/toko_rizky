<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    { {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            Permission::truncate();
            // User
            Permission::create(['name' => 'read_user_management']);
            Permission::create(['name' => 'create_user_management']);
            Permission::create(['name' => 'update_user_management']);
            Permission::create(['name' => 'delete_user_management']);
            Permission::create(['name' => 'reset_password_user_management']);

            // Role
            Permission::create(['name' => 'read_role']);
            Permission::create(['name' => 'create_role']);
            Permission::create(['name' => 'update_role']);
            Permission::create(['name' => 'delete_role']);

            // Permission
            Permission::create(['name' => 'read_permission']);
            Permission::create(['name' => 'create_permission']);
            Permission::create(['name' => 'update_permission']);
            Permission::create(['name' => 'delete_permission']);

            //categories
            Permission::create(['name' => 'read_categories']);
            Permission::create(['name' => 'create_categories']);
            Permission::create(['name' => 'update_categories']);
            Permission::create(['name' => 'delete_categories']);
            //unit larges
            Permission::create(['name' => 'read_unit_larges']);
            Permission::create(['name' => 'create_unit_larges']);
            Permission::create(['name' => 'update_unit_larges']);
            Permission::create(['name' => 'delete_unit_larges']);

            Permission::create(['name' => 'read_unit_smalls']);
            Permission::create(['name' => 'create_unit_smalls']);
            Permission::create(['name' => 'update_unit_smalls']);
            Permission::create(['name' => 'delete_unit_smalls']);

            Permission::create(['name' => 'read_suppliers']);
            Permission::create(['name' => 'create_suppliers']);
            Permission::create(['name' => 'update_suppliers']);
            Permission::create(['name' => 'delete_suppliers']);


            Permission::create(['name' => 'read_branches']);
            Permission::create(['name' => 'create_branches']);
            Permission::create(['name' => 'update_branches']);
            Permission::create(['name' => 'delete_branches']);
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}
