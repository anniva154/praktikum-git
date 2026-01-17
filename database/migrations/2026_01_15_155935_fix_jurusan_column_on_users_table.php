<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // hapus jurusan_id kalau ada
            if (Schema::hasColumn('users', 'jurusan_id')) {
                $table->dropForeign(['jurusan_id']);
                $table->dropColumn('jurusan_id');
            }

            // tambah id_jurusan
            if (!Schema::hasColumn('users', 'id_jurusan')) {
                $table->unsignedBigInteger('id_jurusan')->after('role');
            }

            // foreign key ke jurusan.id_jurusan
            $table->foreign('id_jurusan')
                  ->references('id_jurusan')
                  ->on('jurusan')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'id_jurusan')) {
                $table->dropForeign(['id_jurusan']);
                $table->dropColumn('id_jurusan');
            }
        });
    }
};
