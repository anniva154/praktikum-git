<?php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id('id_peminjaman');
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_barang');
            $table->date('tgl_pinjam');
            $table->date('tgl_kembali')->nullable();
            $table->integer('jumlah_pinjam');
            $table->string('status', 50);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Foreign Key
            $table->foreign('id_user')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->foreign('id_barang')
                  ->references('id_barang') // ✅ sesuai PK tabel barang
                  ->on('barang')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman'); // ⚠️ typo juga aku betulkan
    }
};
