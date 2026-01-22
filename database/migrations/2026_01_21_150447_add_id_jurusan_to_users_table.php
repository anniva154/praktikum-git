<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('id_jurusan')
                  ->nullable()
                  ->after('tipe_pengguna');

            $table->foreign('id_jurusan')
                  ->references('id_jurusan')
                  ->on('jurusan')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['id_jurusan']);
            $table->dropColumn('id_jurusan');
        });
    }
};
