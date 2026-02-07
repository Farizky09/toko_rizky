<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branches = DB::table('branches')->get();
        $locations = [];

        foreach ($branches as $branch) {
            $locations[] = [
                'branch_id' => $branch->id,
                'name' => 'Gudang ' . $branch->city,
                'type' => 'warehouse',
                'status' => 'active',

            ];

            $locations[] = [
                'branch_id' => $branch->id,
                'name' => 'Toko ' . $branch->city,
                'type' => 'store',
                'status' => 'active',
            ];
        }

        DB::table('locations')->insert($locations);
    }
}
