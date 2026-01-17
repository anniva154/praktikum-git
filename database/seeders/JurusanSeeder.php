<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JurusanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jurusan')->insert([
            ['nama_jurusan' => 'TKJ'],
            ['nama_jurusan' => 'Farmasi'],
            ['nama_jurusan' => 'Perhotelan'],
        ]);
    }
}

