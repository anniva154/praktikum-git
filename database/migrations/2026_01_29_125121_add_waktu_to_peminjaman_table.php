<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dateTime('waktu_pinjam')
                  ->nullable()
                  ->after('id_barang');

            $table->dateTime('waktu_kembali')
                  ->nullable()
                  ->after('waktu_pinjam');
        });

        // 🔁 MIGRASI DATA LAMA → DATETIME
        DB::statement("
            UPDATE peminjaman
            SET waktu_pinjam = CONCAT(tgl_pinjam, ' 08:00:00')
            WHERE waktu_pinjam IS NULL
        ");

        DB::statement("
            UPDATE peminjaman
            SET waktu_kembali = CONCAT(tgl_kembali, ' 16:00:00')
            WHERE tgl_kembali IS NOT NULL
        ");
    }

    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn(['waktu_pinjam', 'waktu_kembali']);
        });
    }
};
