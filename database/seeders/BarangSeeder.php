<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('barang')->insert([
            [
               'nama_barang' => 'Komputer PC',
'kode_barang' => 'RTR-TKJ-001',
'jumlah' => 25, // silakan ganti sesuai kebutuhan
'kondisi' => 'Baik',
'id_jurusan' => 1,
'id_lab' => 2,
'created_at' => now(),
'updated_at' => now(),

            ],
        ]);
    }
}
