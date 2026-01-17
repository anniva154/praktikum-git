<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laboratorium', function (Blueprint $table) {
            $table->enum('status', ['kosong', 'dipakai', 'perbaikan'])
                  ->default('kosong')
                  ->after('id_jurusan');

            $table->text('keterangan')
                  ->nullable()
                  ->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('laboratorium', function (Blueprint $table) {
            $table->dropColumn(['status', 'keterangan']);
        });
    }
};
