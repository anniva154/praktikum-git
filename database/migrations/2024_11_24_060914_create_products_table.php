<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('summary');
            $table->longText('description')->nullable();
            $table->text('photo');
            $table->integer('stock')->default(1);
            $table->enum('condition', ['default', 'new', 'hot'])->default('default');
            $table->enum('status', ['active', 'inactive'])->default('inactive');
            $table->float('price');
            $table->float('discount')->nullable(); // Discount bisa null
            $table->boolean('is_featured')->default(false); // Produk unggulan (featured)
            $table->unsignedBigInteger('cat_id')->nullable(); // Kategori utama
            $table->unsignedBigInteger('child_cat_id')->nullable(); // Subkategori
            $table->unsignedBigInteger('brand_id')->nullable(); // Brand (jika ada)
            
            // Kolom tambahan
            $table->json('colors')->nullable(); // Variasi warna produk
            $table->json('size')->nullable(); // Variasi ukuran (JSON, untuk hijab instan, pasmina, dll)
            
            // Foreign key relationships
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('SET NULL');
            $table->foreign('cat_id')->references('id')->on('categories')->onDelete('SET NULL');
            $table->foreign('child_cat_id')->references('id')->on('categories')->onDelete('SET NULL');

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
        Schema::dropIfExists('products');
    }
}
