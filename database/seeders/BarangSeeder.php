<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    public function run(): void
    {

        $barangs = [

            // ===== LAB KOMPUTER 1 (ID: 4) =====
            ['id_jurusan'=>1,'id_lab'=>4,'nama_barang'=>'PC Client Lenovo','kode_barang'=>'TKJ-L1-PC01','jumlah'=>10,'kondisi'=>'baik','status'=>'tersedia'],
            ['id_jurusan'=>1,'id_lab'=>4,'nama_barang'=>'Monitor Dell 19 Inch','kode_barang'=>'TKJ-L1-MON01','jumlah'=>10,'kondisi'=>'baik','status'=>'tersedia'],
            ['id_jurusan'=>1,'id_lab'=>4,'nama_barang'=>'Keyboard Logitech','kode_barang'=>'TKJ-L1-KB01','jumlah'=>10,'kondisi'=>'baik','status'=>'tersedia'],
            ['id_jurusan'=>1,'id_lab'=>4,'nama_barang'=>'Mouse Logitech','kode_barang'=>'TKJ-L1-MS01','jumlah'=>10,'kondisi'=>'baik','status'=>'tersedia'],
            ['id_jurusan'=>1,'id_lab'=>4,'nama_barang'=>'Printer Epson L3110','kode_barang'=>'TKJ-L1-PR01','jumlah'=>1,'kondisi'=>'baik','status'=>'tersedia'],
            ['id_jurusan'=>1,'id_lab'=>4,'nama_barang'=>'Projector Epson','kode_barang'=>'TKJ-L1-PJ01','jumlah'=>1,'kondisi'=>'baik','status'=>'tersedia'],
            ['id_jurusan'=>1,'id_lab'=>4,'nama_barang'=>'Speaker Aktif','kode_barang'=>'TKJ-L1-SP01','jumlah'=>2,'kondisi'=>'baik','status'=>'tersedia'],
            ['id_jurusan'=>1,'id_lab'=>4,'nama_barang'=>'UPS APC','kode_barang'=>'TKJ-L1-UPS01','jumlah'=>5,'kondisi'=>'baik','status'=>'tersedia'],
            ['id_jurusan'=>1,'id_lab'=>4,'nama_barang'=>'Stabilizer Listrik','kode_barang'=>'TKJ-L1-ST01','jumlah'=>2,'kondisi'=>'baik','status'=>'tersedia'],
            ['id_jurusan'=>1,'id_lab'=>4,'nama_barang'=>'Kabel LAN','kode_barang'=>'TKJ-L1-LAN01','jumlah'=>20,'kondisi'=>'baik','status'=>'tersedia'],

            // ===== LAB KOMPUTER 2 (ID: 5) =====
            ['id_jurusan'=>1,'id_lab'=>5,'nama_barang'=>'Router Mikrotik','kode_barang'=>'TKJ-L2-RT01','jumlah'=>5,'kondisi'=>'baik','status'=>'tersedia'],
            ['id_jurusan'=>1,'id_lab'=>5,'nama_barang'=>'Switch TP-Link 24 Port','kode_barang'=>'TKJ-L2-SW01','jumlah'=>3,'kondisi'=>'baik','status'=>'tersedia'],
            ['id_jurusan'=>1,'id_lab'=>5,'nama_barang'=>'Access Point Ubiquiti','kode_barang'=>'TKJ-L2-AP01','jumlah'=>4,'kondisi'=>'baik','status'=>'tersedia'],
            ['id_jurusan'=>1,'id_lab'=>5,'nama_barang'=>'Kabel LAN Cat6','kode_barang'=>'TKJ-L2-LAN01','jumlah'=>30,'kondisi'=>'baik','status'=>'tersedia'],
            ['id_jurusan'=>1,'id_lab'=>5,'nama_barang'=>'Crimping Tool','kode_barang'=>'TKJ-L2-CT01','jumlah'=>3,'kondisi'=>'baik','status'=>'tersedia'],
            ['id_jurusan'=>1,'id_lab'=>5,'nama_barang'=>'LAN Tester','kode_barang'=>'TKJ-L2-LT01','jumlah'=>2,'kondisi'=>'baik','status'=>'tersedia'],
            ['id_jurusan'=>1,'id_lab'=>5,'nama_barang'=>'Rack Server','kode_barang'=>'TKJ-L2-RS01','jumlah'=>1,'kondisi'=>'baik','status'=>'tersedia'],
            ['id_jurusan'=>1,'id_lab'=>5,'nama_barang'=>'Patch Panel','kode_barang'=>'TKJ-L2-PP01','jumlah'=>2,'kondisi'=>'baik','status'=>'tersedia'],
            ['id_jurusan'=>1,'id_lab'=>5,'nama_barang'=>'Tang Potong Kabel','kode_barang'=>'TKJ-L2-TK01','jumlah'=>4,'kondisi'=>'baik','status'=>'tersedia'],
            ['id_jurusan'=>1,'id_lab'=>5,'nama_barang'=>'Power Supply Tester','kode_barang'=>'TKJ-L2-PST01','jumlah'=>2,'kondisi'=>'baik','status'=>'tersedia'],
        ];

        foreach ($barangs as $barang) {
            DB::table('barang')->insert(array_merge($barang, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}