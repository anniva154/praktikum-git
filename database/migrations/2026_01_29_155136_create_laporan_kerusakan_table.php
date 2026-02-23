<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('laporan_kerusakan', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_barang');
            $table->unsignedBigInteger('id_lab');

            $table->date('tgl_laporan');

            $table->string('foto')->nullable();

            $table->text('keterangan')->nullable();

            $table->enum('status', [
                'diajukan',
                'diproses',
                'selesai',
                'ditolak'
            ])->default('diajukan');

            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();

            // 🔗 OPTIONAL: FOREIGN KEY (kalau tabelnya ada)
            /*
            $table->foreign('id_user')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('id_barang')->references('id_barang')->on('barang')->cascadeOnDelete();
            $table->foreign('id_lab')->references('id_lab')->on('laboratorium')->cascadeOnDelete();
            */
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_kerusakan');
    }
};

