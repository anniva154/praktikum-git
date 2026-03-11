<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PeminjamanSeeder extends Seeder
{
    public function run()
    {
        // Filter Lab ID 4 dan 5 untuk Jurusan TKJ (ID: 1)
        $labIds = [4, 5];
        $idJurusan = 1; // TKJ

        foreach ($labIds as $labId) {
            // Mengambil barang yang ada di lab dan jurusan terkait
            $barangIds = DB::table('barang')
                ->where('id_lab', $labId)
                ->where('id_jurusan', $idJurusan)
                ->pluck('id_barang');

            if ($barangIds->isNotEmpty()) {
                // Menambahkan 3 data peminjaman per Lab
                for ($i = 0; $i < 3; $i++) {
                    DB::table('peminjaman')->insert([
                        'id_user'      => 4, // Contoh ID User (Guru/Kaproli)
                        'id_barang'    => $barangIds->random(),
                        'waktu_pinjam' => Carbon::now()->subDays(rand(1, 7))->setHour(8)->setMinute(0),
                        'waktu_kembali'=> Carbon::now()->addDays(rand(1, 2))->setHour(16)->setMinute(0),
                        'jumlah_pinjam'=> rand(1, 3),
                        'status'       => 'disetujui',
                        'keterangan'   => "Praktik Kompetensi Keahlian TKJ di Lab $labId",
                        'created_at'   => Carbon::now(),
                        'updated_at'   => Carbon::now(),
                    ]);
                }
            }
        }
    }
}