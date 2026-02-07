<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitSmallsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
  public function run(): void
    {
        DB::table('unit_smalls')->insert([
            ['name' => 'Pcs', 'abbreviation' => 'pcs'],
            ['name' => 'Liter', 'abbreviation' => 'L'],
            ['name' => 'Mililiter', 'abbreviation' => 'ml'],
            ['name' => 'Gram', 'abbreviation' => 'g'],
            ['name' => 'Kilogram', 'abbreviation' => 'kg'],
            ['name' => 'Meter', 'abbreviation' => 'm'],
            ['name' => 'Centimeter', 'abbreviation' => 'cm'],
            ['name' => 'Lembar', 'abbreviation' => 'lbr'],
            ['name' => 'Botol', 'abbreviation' => 'btl'],
            ['name' => 'Kaleng', 'abbreviation' => 'klg'],
            ['name' => 'Sachet', 'abbreviation' => 'sct'],
            ['name' => 'Bungkus', 'abbreviation' => 'bks'],
            ['name' => 'Tablet', 'abbreviation' => 'tbl'],
            ['name' => 'Butir', 'abbreviation' => 'btr'],
            ['name' => 'Unit', 'abbreviation' => 'unit'],
            ['name' => 'Pack', 'abbreviation' => 'pack'],
            ['name' => 'Box', 'abbreviation' => 'box'],
            ['name' => 'Cup', 'abbreviation' => 'cup'],
            ['name' => 'Tangkai', 'abbreviation' => 'tkg'],
            ['name' => 'Batang', 'abbreviation' => 'btg'],
        ]);
    }
}
