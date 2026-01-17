<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laboratorium', function (Blueprint $table) {
            $table->id('id_lab');
            $table->string('nama_lab', 100);
            $table->unsignedBigInteger('id_jurusan');
            $table->timestamps();

            // Foreign Key
            $table->foreign('id_jurusan')
                ->references('id_jurusan')
                ->on('jurusan')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laboratorium');
    }
};
