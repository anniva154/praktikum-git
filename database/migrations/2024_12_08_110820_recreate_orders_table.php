<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RecreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Menghapus tabel orders jika sudah ada
        Schema::dropIfExists('orders');

        // Membuat tabel orders dengan struktur baru
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // Nomor pesanan unik
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('SET NULL'); // Relasi ke tabel users
            $table->bigInteger('sub_total'); // Total harga barang (dalam satuan kecil seperti sen atau rupiah)
            $table->bigInteger('coupon')->nullable(); // Diskon kupon (dalam satuan kecil)
            $table->bigInteger('total_amount'); // Total akhir setelah diskon (dalam satuan kecil)
            $table->integer('quantity'); // Total barang dalam pesanan
            $table->enum('payment_status', ['paid', 'unpaid'])->default('unpaid'); // Status pembayaran
            $table->enum('order_status', ['new', 'processing', 'delivered', 'cancelled'])->default('new'); // Status pesanan
            $table->foreignId('shipping_id')->nullable();
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
        // Membalikkan perubahan jika migrasi di-rollback
        Schema::dropIfExists('orders');
    }
}
