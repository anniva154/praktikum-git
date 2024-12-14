<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RecreateShippingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Hapus tabel shippings jika sudah ada
        Schema::dropIfExists('shippings');

        // Buat ulang tabel shippings
        Schema::create('shippings', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // Jenis pengiriman
            $table->decimal('price', 15, 2); // Harga pengiriman
            $table->enum('status', ['active', 'inactive'])->default('active'); // Status pengiriman
            $table->string('courier')->nullable(); // Nama kurir
            $table->string('delivery_time_estimate')->nullable(); // Estimasi waktu pengiriman
            $table->string('tracking_number')->nullable(); // Nomor pelacakan
            $table->boolean('is_default')->default(false); // Apakah pengiriman ini default
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Menghapus tabel shippings
        Schema::dropIfExists('shippings');
    }
}
