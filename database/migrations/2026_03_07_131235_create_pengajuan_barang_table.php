<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
 public function up()
{
    // Penting: Hapus tabel lama jika ada sisa kegagalan sebelumnya agar bersih
    Schema::dropIfExists('pengajuan_barang');

    Schema::create('pengajuan_barang', function (Blueprint $table) {
        $table->id('id_pengajuan');
        $table->unsignedBigInteger('id_user'); 
        $table->unsignedBigInteger('id_jurusan'); 
        $table->unsignedBigInteger('id_lab'); 
        
        $table->string('nama_barang'); 
        $table->text('spesifikasi')->nullable(); 
        $table->integer('jumlah');
        $table->string('satuan'); 
        $table->decimal('estimasi_harga', 15, 2)->nullable(); 
        $table->enum('urgensi', ['Penting Sekali', 'Biasa', 'Persediaan'])->default('Biasa');
        $table->text('alasan_kebutuhan'); 
        $table->enum('status_persetujuan', ['Pending', 'Disetujui', 'Ditolak'])->default('Pending');
        $table->text('catatan_pimpinan')->nullable(); 
        $table->timestamps();

        // RELASI FOREIGN KEY:
        // 1. Ke tabel users (mengacu pada kolom 'id')
        $table->foreign('id_user')->references('id')->on('users');
        
        // 2. Ke tabel jurusan (mengacu pada kolom 'id_jurusan' sesuai gambar Anda)
        $table->foreign('id_jurusan')->references('id_jurusan')->on('jurusan');
        
        // 3. Ke tabel laboratorium (mengacu pada kolom 'id_lab')
        $table->foreign('id_lab')->references('id_lab')->on('laboratorium');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_barang');
    }
};
