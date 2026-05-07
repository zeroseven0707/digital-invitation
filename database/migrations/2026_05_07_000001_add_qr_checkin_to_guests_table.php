<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            $table->string('qr_token', 64)->nullable()->unique()->after('whatsapp_number');
            $table->timestamp('checked_in_at')->nullable()->after('qr_token');
        });
    }

    public function down(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            $table->dropColumn(['qr_token', 'checked_in_at']);
        });
    }
};
