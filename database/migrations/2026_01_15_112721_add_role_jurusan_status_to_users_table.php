<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // tambah jurusan_id jika belum ada
            if (!Schema::hasColumn('users', 'jurusan_id')) {
                $table->foreignId('jurusan_id')
                      ->nullable()
                      ->after('role');
            }

            // tambah status jika belum ada
            if (!Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['aktif', 'nonaktif'])
                      ->default('aktif')
                      ->after('jurusan_id');
            }
        });

        // pisahkan foreign key (lebih aman)
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'jurusan_id')) {
                $table->foreign('jurusan_id')
                      ->references('id')
                      ->on('jurusan')
                      ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // drop FK dulu
            if (Schema::hasColumn('users', 'jurusan_id')) {
                $table->dropForeign(['jurusan_id']);
                $table->dropColumn('jurusan_id');
            }

            if (Schema::hasColumn('users', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
