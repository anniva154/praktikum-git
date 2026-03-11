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
        Schema::create('mata_pelajaran', function (Blueprint $table) {
    $table->id(); 
    // Relasi ke id_jurusan di tabel jurusan
    $table->foreignId('id_jurusan')
          ->constrained('jurusan', 'id_jurusan') 
          ->onDelete('cascade');
    
    $table->string('nama_mapel');
    $table->string('kode_mapel')->unique();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mata_pelajaran');
    }
};
