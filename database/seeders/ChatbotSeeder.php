<?php

namespace Database\Seeders;

use App\Models\Chatbot;
use Illuminate\Database\Seeder;

class ChatbotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'keyword' => 'pinjam',
                'jawaban' => 'Untuk meminjam alat atau ruangan laboratorium, Anda harus Masuk (Login) terlebih dahulu. Jika belum punya akun, silakan klik tombol Daftar.'
            ],
            [
                'keyword' => 'prosedur',
                'jawaban' => 'Prosedur peminjaman: 1. Login, 2. Pilih alat di menu Barang Lab, 3. Klik Pinjam, 4. Tunggu validasi dari Kaproli.'
            ],
            [
                'keyword' => 'tkj',
                'jawaban' => 'Laboratorium TKJ (Teknik Komputer dan Jaringan) fokus pada praktik perakitan komputer, instalasi OS, dan konfigurasi jaringan.'
            ],
            [
                'keyword' => 'jam operasional',
                'jawaban' => 'Laboratorium SMKN 3 Bangkalan melayani praktik pada hari kerja mulai pukul 07.00 hingga 15.30 WIB.'
            ],
            [
                'keyword' => 'rusak',
                'jawaban' => 'Jika menemukan alat yang rusak, mohon segera buat Laporan Kerusakan di dashboard akun Anda setelah login agar segera ditangani teknisi.'
            ],
            [
                'keyword' => 'kontak',
                'jawaban' => 'Anda dapat menghubungi admin lab melalui Telp: (031) 3062126 atau email: smkn3bangkalan.adm@gmail.com.'
            ],
            [
                'keyword' => 'farmasi',
                'jawaban' => 'Laboratorium Farmasi digunakan untuk praktik pembuatan obat dan analisis kimia dasar bagi siswa jurusan Farmasi.'
            ],
        ];

        foreach ($data as $item) {
            Chatbot::create($item);
        }
    }
}