<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SuppliersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            ['name' => 'PT Sumber Rezeki Abadi', 'address' => 'Jl. Merdeka No. 12, Jakarta', 'phone' => '081234567890'],
            ['name' => 'CV Maju Bersama', 'address' => 'Jl. Raya Bandung No. 45, Bandung', 'phone' => '082134567891'],
            ['name' => 'PT Cahaya Gemilang', 'address' => 'Jl. Diponegoro No. 10, Surabaya', 'phone' => '083134567892'],
            ['name' => 'UD Sejahtera', 'address' => 'Jl. Malioboro No. 22, Yogyakarta', 'phone' => '081356789012'],
            ['name' => 'PT Makmur Sentosa', 'address' => 'Jl. Ahmad Yani No. 88, Semarang', 'phone' => '085612345678'],
            ['name' => 'CV Berkah Jaya', 'address' => 'Jl. Gajah Mada No. 5, Medan', 'phone' => '081278945612'],
            ['name' => 'PT Mitra Niaga', 'address' => 'Jl. Soekarno Hatta No. 77, Palembang', 'phone' => '081356712345'],
            ['name' => 'CV Indo Mandiri', 'address' => 'Jl. Sudirman No. 99, Pekanbaru', 'phone' => '082167894512'],
            ['name' => 'PT Global Sukses', 'address' => 'Jl. Diponegoro No. 3, Malang', 'phone' => '081345678901'],
            ['name' => 'CV Anugerah Sejati', 'address' => 'Jl. Rajawali No. 8, Denpasar', 'phone' => '081234123456'],
            ['name' => 'PT Bumi Lestari', 'address' => 'Jl. Pahlawan No. 11, Balikpapan', 'phone' => '082134789456'],
            ['name' => 'CV Sentosa Abadi', 'address' => 'Jl. Imam Bonjol No. 6, Pontianak', 'phone' => '083123456789'],
            ['name' => 'PT Nusantara Makmur', 'address' => 'Jl. Ahmad Dahlan No. 14, Makassar', 'phone' => '081234987654'],
            ['name' => 'CV Sinar Baru', 'address' => 'Jl. Anggrek No. 9, Samarinda', 'phone' => '081267845612'],
            ['name' => 'PT Cipta Mandiri', 'address' => 'Jl. Melati No. 23, Bogor', 'phone' => '085267845678'],
            ['name' => 'CV Berkat Abadi', 'address' => 'Jl. Mawar No. 10, Depok', 'phone' => '081278456789'],
            ['name' => 'PT Karya Utama', 'address' => 'Jl. Kenanga No. 7, Tangerang', 'phone' => '082178945612'],
            ['name' => 'CV Tunas Harapan', 'address' => 'Jl. Flamboyan No. 2, Bekasi', 'phone' => '081312345678'],
            ['name' => 'PT Indo Pratama', 'address' => 'Jl. Merpati No. 4, Cirebon', 'phone' => '081256789123'],
            ['name' => 'CV Mega Jaya', 'address' => 'Jl. Kamboja No. 15, Banyuwangi', 'phone' => '081234789012'],
        ];

        DB::table('suppliers')->insert($suppliers);
    }
}
