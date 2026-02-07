<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            ['name' => 'Sembako'],
            ['name' => 'Minuman'],
            ['name' => 'Makanan Ringan'],
            ['name' => 'Bumbu Dapur'],
            ['name' => 'Kebutuhan Rumah Tangga'],
            ['name' => 'Pakaian & Aksesoris'],
            ['name' => 'Perlengkapan Mandi'],
            ['name' => 'Peralatan Dapur'],
            ['name' => 'Produk Susu'],
            ['name' => 'Rokok & Tembakau'],
            ['name' => 'Perawatan Bayi'],
            ['name' => 'Elektronik Kecil'],
            ['name' => 'Obat & Kesehatan'],
            ['name' => 'ATK & Sekolah'],
            ['name' => 'Otomotif & Aki'],
            ['name' => 'Peralatan Kebersihan'],
            ['name' => 'Alat Listrik'],
            ['name' => 'Kosmetik & Parfum'],
            ['name' => 'Frozen Food'],
            ['name' => 'Bahan Bangunan'],
            ['name' => 'Peralatan Kantor'],
            ['name' => 'Aksesoris Gadget'],
            ['name' => 'Alat Tulis & Kertas'],
            ['name' => 'Makanan Hewan'],
            ['name' => 'Produk Herbal'],
            ['name' => 'Snack Import'],
            ['name' => 'Buah & Sayur'],
            ['name' => 'Daging & Ikan'],
            ['name' => 'Minyak & Lemak'],
            ['name' => 'Gas & Energi'],
            ['name' => 'Laundry & Pewangi'],
        ]);
    }
}
