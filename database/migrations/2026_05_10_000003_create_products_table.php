<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');                          // Dispenser, Rice Cooker, dll
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();        // foto produk
            $table->bigInteger('price');                     // harga dalam IDR
            $table->integer('stock')->default(1);            // stok tersedia
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
