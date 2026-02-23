<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $data = [

            // ===== UTENSIL =====
            ['Glass cloth', 'FB2-UT-01', 12],
            ['Napkin Merah', 'FB2-UT-02', 12],
            ['Napkin Hitam', 'FB2-UT-03', 11],
            ['Napkin Coklat', 'FB2-UT-04', 12],
            ['Ice Bucket', 'FB2-UT-05', 1],
            ['Shaker', 'FB2-UT-06', 2],
            ['Jigger Stainless', 'FB2-UT-07', 2],
            ['Jigger Melamin', 'FB2-UT-08', 2],
            ['Bar Muddler', 'FB2-UT-09', 2],
            ['Bar Spoon', 'FB2-UT-10', 2],
            ['Strainer', 'FB2-UT-11', 3],
            ['Mixing Glass', 'FB2-UT-12', 2],
            ['Ice Tong', 'FB2-UT-13', 2],
            ['Bread Tong', 'FB2-UT-14', 6],
            ['Fruit Tong', 'FB2-UT-15', 3],
            ['Juicer', 'FB2-UT-16', 1],
            ['Ice Cream Cup', 'FB2-UT-17', 2],
            ['Gelas Ukur 100 ml', 'FB2-UT-18', 2],
            ['Brush Machine', 'FB2-UT-19', 1],
            ['Brush Coffee', 'FB2-UT-20', 1],
            ['Pourer Stainless', 'FB2-UT-21', 8],
            ['Scale Digital', 'FB2-UT-22', 6],
            ['Milk Jug Stainless', 'FB2-UT-23', 2],
            ['Tea/Coffee Pot Stainless', 'FB2-UT-24', 5],
            ['Food Grade Container', 'FB2-UT-25', 1],
            ['Tamper Mat', 'FB2-UT-26', 1],
            ['Tamper', 'FB2-UT-27', 1],
            ['Portafilter', 'FB2-UT-28', 1],
            ['Knock Box', 'FB2-UT-29', 1],
            ['Cutting Board Bar', 'FB2-UT-30', 1],
            ['Apron', 'FB2-UT-31', 3],
            ['Set Knife Oxone', 'FB2-UT-32', 1],
            ['Ladle', 'FB2-UT-33', 4],
            ['Saringan Besar', 'FB2-UT-34', 1],
            ['Rice Ladle', 'FB2-UT-35', 1],
            ['Saringan Kecil', 'FB2-UT-36', 3],
            ['Spatula', 'FB2-UT-37', 2],
            ['Tray', 'FB2-UT-38', 9],
            ['Bill Tray', 'FB2-UT-39', 6],
            ['Bill Tray Daun', 'FB2-UT-40', 2],
            ['Round Tray Kayu', 'FB2-UT-41', 1],
            ['Tray Kayu', 'FB2-UT-42', 3],
            ['Number Table', 'FB2-UT-43', 6],
            ['Cutting Board Besar', 'FB2-UT-44', 2],

            // ===== CUTLERIES =====
            ['Dinner Knife', 'FB2-CT-01', 55],
            ['Dinner Spoon', 'FB2-CT-02', 35],
            ['Dinner Fork', 'FB2-CT-03', 48],
            ['Dessert Knife', 'FB2-CT-04', 41],
            ['Dessert Fork', 'FB2-CT-05', 33],
            ['Tea Spoon', 'FB2-CT-06', 52],
            ['Long Spoon', 'FB2-CT-07', 3],
            ['Soup Spoon', 'FB2-CT-08', 50],

            // ===== GLASSWARE =====
            ['Wine Glass', 'FB2-GW-01', 11],
            ['Water Goblet', 'FB2-GW-02', 57],
            ['Highball', 'FB2-GW-03', 4],
            ['Highball Tumbler', 'FB2-GW-04', 8],
            ['Collins Glass', 'FB2-GW-05', 12],
            ['Milkshake Glass', 'FB2-GW-06', 5],
            ['Pilsner Glass', 'FB2-GW-07', 3],
            ['Arcade Glass', 'FB2-GW-08', 4],
            ['Pitcher Water Jug', 'FB2-GW-09', 7],
            ['Flower Vase', 'FB2-GW-10', 6],

            // ===== CHINAWARE =====
            ['Soup Bowl', 'FB2-CH-01', 13],
            ['Soup Cup', 'FB2-CH-02', 6],
            ['Tea/Coffee Cup', 'FB2-CH-03', 37],
            ['Dessert Bowl', 'FB2-CH-04', 23],
            ['Sauce Dish', 'FB2-CH-05', 34],
            ['Sauce Dish Bulat', 'FB2-CH-06', 46],
            ['Sauce Dish Sekat', 'FB2-CH-07', 4],
            ['Sugar Holder', 'FB2-CH-08', 10],
            ['Sugar Bowl', 'FB2-CH-09', 2],
            ['Ashtray', 'FB2-CH-10', 3],
            ['Creamer Jug', 'FB2-CH-11', 4],
            ['Salt & Pepper Shaker', 'FB2-CH-12', 6],
            ['B&B Plate', 'FB2-CH-13', 50],
            ['Dessert Plate', 'FB2-CH-14', 65],
            ['Dinner Plate', 'FB2-CH-15', 63],
        ];

        foreach ($data as $item) {
            DB::table('barang')->insert([
                'nama_barang' => $item[0],
                'kode_barang' => $item[1],
                'jumlah'      => $item[2],
                'kondisi'     => 'baik',
                'status'      => 'aktif',
                'id_jurusan'  => 3, // PERHOTELAN
                'id_lab'      => 3, // LAB PERHOTELAN 2 (FB SERVICE)
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}
