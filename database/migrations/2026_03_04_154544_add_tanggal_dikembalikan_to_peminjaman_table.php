<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::table('peminjaman', function (Blueprint $table) {
        // Kita letakkan setelah kolom status agar rapi
        // Menggunakan datetime dan nullable karena di awal pasti kosong
        $table->datetime('tanggal_dikembalikan')->nullable()->after('status');
    });
}

public function down(): void
{
    Schema::table('peminjaman', function (Blueprint $table) {
        $table->dropColumn('tanggal_dikembalikan');
    });
}

   
};
