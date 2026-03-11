<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PengajuanSeeder extends Seeder
{
    public function run(): void
    {
        $pengajuan = [

            // ===== LAB 4 =====
            [
                'id_user' => 2,
                'id_jurusan' => 1,
                'id_lab' => 4,
                'nama_barang' => 'PC Client Lenovo',
                'spesifikasi' => 'Processor i5, RAM 8GB, SSD 256GB',
                'jumlah' => 10,
                'satuan' => 'Unit',
                'estimasi_harga' => 6500000,
                'urgensi' => 'Penting Sekali',
                'alasan_kebutuhan' => 'Digunakan untuk kegiatan praktikum jaringan komputer',
                'status_persetujuan' => 'Pending',
                'catatan_pimpinan' => null,
            ],
            [
                'id_user' => 2,
                'id_jurusan' => 1,
                'id_lab' => 4,
                'nama_barang' => 'Printer Epson L3210',
                'spesifikasi' => 'Printer Ink Tank All in One',
                'jumlah' => 2,
                'satuan' => 'Unit',
                'estimasi_harga' => 2500000,
                'urgensi' => 'Biasa',
                'alasan_kebutuhan' => 'Untuk mencetak laporan praktikum siswa',
                'status_persetujuan' => 'Pending',
                'catatan_pimpinan' => null,
            ],

            // ===== LAB 5 =====
            [
                'id_user' => 2,
                'id_jurusan' => 1,
                'id_lab' => 5,
                'nama_barang' => 'Router Mikrotik RB750',
                'spesifikasi' => 'Router Mikrotik 5 Port Gigabit',
                'jumlah' => 5,
                'satuan' => 'Unit',
                'estimasi_harga' => 850000,
                'urgensi' => 'Penting Sekali',
                'alasan_kebutuhan' => 'Digunakan untuk praktikum konfigurasi jaringan',
                'status_persetujuan' => 'Pending',
                'catatan_pimpinan' => null,
            ],
            [
                'id_user' => 2,
                'id_jurusan' => 1,
                'id_lab' => 5,
                'nama_barang' => 'LAN Tester',
                'spesifikasi' => 'Alat tester kabel LAN RJ45',
                'jumlah' => 3,
                'satuan' => 'Unit',
                'estimasi_harga' => 150000,
                'urgensi' => 'Persediaan',
                'alasan_kebutuhan' => 'Digunakan untuk mengecek kabel jaringan praktikum',
                'status_persetujuan' => 'Pending',
                'catatan_pimpinan' => null,
            ],
        ];

        foreach ($pengajuan as $item) {
            DB::table('pengajuan_barang')->insert(array_merge($item, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}