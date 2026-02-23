<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barang', function (Blueprint $table) {

            // KONDISI FISIK BARANG
            $table->enum('kondisi', [
                'baik',
                'rusak ringan',
                'rusak berat'
            ])
            ->default('baik')
            ->comment('Kondisi fisik barang')
            ->change();

            // STATUS ASET (BUKAN STATUS TRANSAKSI)
            $table->enum('status', [
                'aktif',
                'tidak layak',
                
            ])
            ->default('aktif')
            ->comment('Status aset barang')
            ->after('kondisi');
        });
    }

    public function down(): void
    {
        Schema::table('barang', function (Blueprint $table) {

            // Kembalikan kondisi ke bentuk awal
            $table->string('kondisi', 100)
                  ->default('baik')
                  ->change();

            // Hapus kolom status
            $table->dropColumn('status');
        });
    }
};
