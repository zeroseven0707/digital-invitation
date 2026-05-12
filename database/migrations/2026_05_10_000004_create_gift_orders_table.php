<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gift_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invitation_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');

            // Pembeli (tamu, tanpa akun)
            $table->string('buyer_name');
            $table->string('buyer_email');
            $table->string('buyer_phone')->nullable();
            $table->string('buyer_message', 500)->nullable();  // pesan untuk pengantin

            // Pembayaran
            $table->string('order_code')->unique();            // GIFT-{id}-{timestamp}
            $table->string('snap_token')->nullable();
            $table->string('transaction_id')->nullable();
            $table->bigInteger('amount');                      // harga saat beli (snapshot)
            $table->string('status')->default('pending');      // pending|paid|cancelled|expired
            $table->string('payment_type')->nullable();
            $table->json('midtrans_response')->nullable();
            $table->timestamp('paid_at')->nullable();

            // Pengiriman
            $table->string('shipping_address')->nullable();    // alamat pengantin (auto-fill)
            $table->string('shipping_status')->default('pending'); // pending|processing|shipped|delivered
            $table->string('tracking_number')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();

            $table->timestamps();

            $table->index(['invitation_id', 'status']);
            $table->index('order_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gift_orders');
    }
};
