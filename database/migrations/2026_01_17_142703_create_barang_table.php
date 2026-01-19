<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->id('id_barang');
            $table->string('nama_barang', 100);
            $table->string('kode_barang', 100);
            $table->integer('jumlah');
            $table->string('kondisi', 100);
            $table->unsignedBigInteger('id_jurusan');
            $table->unsignedBigInteger('id_lab');
            $table->timestamps();

            // foreign key
            $table->foreign('id_jurusan')
                  ->references('id_jurusan')
                  ->on('jurusan')
                  ->onDelete('cascade');

            $table->foreign('id_lab')
                  ->references('id_lab')
                  ->on('laboratorium')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
