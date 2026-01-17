<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed jurusan DULU
        $this->call(JurusanSeeder::class);

        // 2. User contoh (optional)
        User::factory()->create([
            'name' => 'Admin SIMLAB',
            'email' => 'admin@simlab.test',
            'role' => 'admin',
            'status' => 'aktif',
            'jurusan_id' => null
        ]);
    }
}
