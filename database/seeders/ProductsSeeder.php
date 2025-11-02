<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = DB::table('categories')->pluck('id')->toArray();
        $unitLarges = DB::table('unit_larges')->pluck('id')->toArray();
        $unitSmalls = DB::table('unit_smalls')->pluck('id')->toArray();

        $products = [
            ['Beras Premium 5Kg', 'Beras kualitas super untuk konsumsi harian'],
            ['Minyak Goreng Bimoli 2L', 'Minyak goreng nabati kualitas terbaik'],
            ['Gula Pasir Gulaku 1Kg', 'Gula pasir murni manis alami'],
            ['Sabun Lifebuoy Merah', 'Sabun mandi antibakteri'],
            ['Sampo Clear Anti Ketombe', 'Sampo dengan formula anti ketombe'],
            ['Kopi Kapal Api Special Mix', 'Kopi robusta khas Indonesia'],
            ['Teh Botol Sosro', 'Minuman teh manis siap saji'],
            ['Air Mineral Aqua 600ml', 'Air mineral murni dari pegunungan'],
            ['Indomie Goreng Original', 'Mi instan rasa gurih khas Indonesia'],
            ['Tepung Terigu Segitiga Biru', 'Tepung terigu protein sedang'],
            ['Rokok Djarum Super', 'Rokok kretek rasa kuat dan khas'],
            ['Pasta Gigi Pepsodent', 'Pasta gigi dengan fluoride'],
            ['Detergen Rinso Cair', 'Pembersih pakaian lembut di tangan'],
            ['Baterai ABC Medium', 'Baterai kering tahan lama'],
            ['Susu Dancow 1+ 400gr', 'Susu pertumbuhan anak usia 1 tahun ke atas'],
            ['Bumbu Indofood Rendang', 'Bumbu instan siap masak'],
            ['Shampoo Pantene 400ml', 'Menjadikan rambut halus dan lembut'],
            ['Coklat SilverQueen 65gr', 'Coklat susu dengan kacang mete'],
            ['Masker Wajah Garnier', 'Masker wajah pencerah alami'],
            ['Sabun Cuci Piring Sunlight', 'Menghilangkan lemak membandel'],
            ['Tisu Paseo', 'Tisu lembut dan kuat'],
            ['Kecap Manis ABC 600ml', 'Kecap manis khas Indonesia'],
            ['Sarden ABC 425gr', 'Ikan sarden dengan saus tomat pedas'],
            ['Mie Sedap Goreng', 'Mi instan rasa gurih dan nikmat'],
            ['Rokok Marlboro Light', 'Rokok putih ringan'],
            ['Obat Paracetamol 500mg', 'Pereda nyeri dan demam'],
            ['Pulpen Pilot Hitam', 'Pulpen tinta halus dan lancar'],
            ['Sapu Ijuk', 'Peralatan kebersihan rumah tangga'],
            ['Lampu LED Philips 10W', 'Lampu hemat energi'],
            ['Botol Minum Tupperware', 'Botol air BPA Free'],
            ['Nugget So Good 500gr', 'Makanan beku praktis siap goreng'],
            ['Ikan Kembung Segar', 'Ikan laut segar siap masak'],
            ['Sayur Bayam', 'Sayur hijau segar kaya zat besi'],
            ['Obat Herbal Tolak Angin', 'Minuman herbal pereda masuk angin'],
            ['Makanan Kucing Whiskas 1Kg', 'Pakan lengkap untuk kucing dewasa'],
            ['Cairan Pembersih Lantai Wipol', 'Antibakteri dengan aroma pinus'],
            ['Kopi Luwak White Koffie', 'Kopi instan rasa lembut'],
            ['Minyak Kayu Putih Cap Lang', 'Minyak untuk pijat dan masuk angin'],
            ['Mentega Blue Band 200gr', 'Mentega serbaguna untuk masakan'],
            ['Tepung Bumbu Sajiku', 'Tepung bumbu serbaguna'],
            ['Sikat Gigi Formula', 'Sikat gigi lembut membersihkan maksimal'],
            ['Karton Kardus Kecil', 'Kemasan barang ringan'],
            ['Sabun Bayi Zwitsal', 'Sabun lembut untuk kulit bayi'],
            ['Obat Batuk Komix', 'Obat sirup batuk herbal'],
            ['Kertas HVS A4 80gr', 'Kertas cetak dan tulis berkualitas'],
            ['Pewangi Downy', 'Pewangi pakaian tahan lama'],
            ['Beras Setra Ramos 10Kg', 'Beras pulen dan wangi'],
            ['Air Galon Le Minerale', 'Air mineral isi ulang 19L'],
            ['Saus Sambal ABC 335ml', 'Saus sambal pedas mantap'],
            ['Kecap Bango 1L', 'Kecap manis dengan kedelai pilihan'],
        ];

        $data = [];
        $counter = 1;

        foreach ($products as $p) {
            $data[] = [
                'code' => 'PRD-' . str_pad($counter, 4, '0', STR_PAD_LEFT),
                'name' => $p[0],
                'category_id' => $categories[array_rand($categories)],
                'unit_large_id' => $unitLarges[array_rand($unitLarges)],
                'unit_small_id' => $unitSmalls[array_rand($unitSmalls)],
                'conversion' => rand(6, 24), // contoh: 1 kardus = 12 pcs
                'min_stock' => 5,
                'status' => 'active',
                'description' => $p[1],
            ];
            $counter++;
        }

        DB::table('products')->insert($data);
    }
}
