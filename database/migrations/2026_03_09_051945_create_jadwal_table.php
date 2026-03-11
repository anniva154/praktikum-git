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
        Schema::create('jadwal', function (Blueprint $table) {
    $table->id();
    // Relasi ke tabel yang menggunakan Primary Key khusus
    $table->foreignId('id_jurusan')->constrained('jurusan', 'id_jurusan');
    $table->foreignId('id_lab')->constrained('laboratorium', 'id_lab');
    
    // Relasi ke tabel mata_pelajaran yang baru dibuat
    $table->foreignId('id_mapel')->constrained('mata_pelajaran'); 
    $table->foreignId('id_user')->constrained('users'); 

    $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']);
    $table->time('jam_mulai');
    $table->time('jam_selesai');
    $table->string('kelas'); 
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal');
    }
};
