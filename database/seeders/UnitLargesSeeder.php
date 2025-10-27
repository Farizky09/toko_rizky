<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitLargesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('unit_larges')->insert([
            ['name' => 'Kardus', 'abbreviation' => 'crt'],
            ['name' => 'Dus', 'abbreviation' => 'dus'],
            ['name' => 'Pack Besar', 'abbreviation' => 'pkb'],
            ['name' => 'Galon', 'abbreviation' => 'gal'],
            ['name' => 'Bal', 'abbreviation' => 'bal'],
            ['name' => 'Sak', 'abbreviation' => 'sak'],
            ['name' => 'Box Besar', 'abbreviation' => 'bx'],
            ['name' => 'Karung', 'abbreviation' => 'krg'],
            ['name' => 'Keranjang', 'abbreviation' => 'krj'],
            ['name' => 'Paket', 'abbreviation' => 'pkt'],
            ['name' => 'Bundle', 'abbreviation' => 'bndl'],
            ['name' => 'Drum', 'abbreviation' => 'drm'],
            ['name' => 'Tong', 'abbreviation' => 'tng'],
            ['name' => 'Peti', 'abbreviation' => 'pt'],
            ['name' => 'Roll', 'abbreviation' => 'roll'],
            ['name' => 'Slop', 'abbreviation' => 'slp'],
            ['name' => 'Rak', 'abbreviation' => 'rk'],
            ['name' => 'Kotak', 'abbreviation' => 'ktk'],
            ['name' => 'Bundle Pallet', 'abbreviation' => 'plt'],
            ['name' => 'Karton', 'abbreviation' => 'ktn'],
        ]);
    }
}
