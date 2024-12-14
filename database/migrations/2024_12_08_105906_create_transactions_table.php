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
        Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        $table->string('order_id');
        $table->string('customer_name')->nullable();
        $table->integer('gross_amount')->nullable();
        $table->enum('payment_type', ['credit_card', 'bank_transfer','qris', 'cstore', 'echannel'])->nullable();
        $table->string('payment_method')->nullable();
        $table->enum('courier', ['jne', 'tiki', 'pos'])->nullable();
        $table->string('courier_service')->nullable();
        $table->string('transaction_status')->nullable();
        $table->datetime('transaction_time')->nullable();
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
