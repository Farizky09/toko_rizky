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
    {
        { {
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                Permission::truncate();
                // User
                Permission::create(['name' => 'create_user_management']);
                Permission::create(['name' => 'read_user_management']);
                Permission::create(['name' => 'update_user_management']);
                Permission::create(['name' => 'delete_user_management']);
                Permission::create(['name' => 'resetPassword_user_management']);

                // Role
                Permission::create(['name' => 'create_role']);
                Permission::create(['name' => 'read_role']);
                Permission::create(['name' => 'update_role']);
                Permission::create(['name' => 'delete_role']);

                // Permission
                Permission::create(['name' => 'create_permission']);
                Permission::create(['name' => 'read_permission']);
                Permission::create(['name' => 'update_permission']);
                Permission::create(['name' => 'delete_permission']);


                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            }
        }
    }
}
