<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invitation_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('order_id')->unique();          // INV-{invitation_id}-{timestamp}
            $table->string('snap_token')->nullable();      // Midtrans Snap token
            $table->string('transaction_id')->nullable();  // Midtrans transaction ID
            $table->bigInteger('amount');                  // in IDR
            $table->string('status')->default('pending');  // pending | success | failed | expired | cancel
            $table->string('payment_type')->nullable();    // credit_card, gopay, bca_va, etc.
            $table->json('midtrans_response')->nullable(); // raw Midtrans notification
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['invitation_id', 'status']);
            $table->index('order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
