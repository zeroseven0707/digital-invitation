<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            // phone / whatsapp — unify into one field, keep whatsapp_number as alias
            // whatsapp_number already exists from previous migration, add phone as alias
            $table->string('phone', 20)->nullable()->after('whatsapp_number');
            $table->string('email', 255)->nullable()->after('phone');
            $table->text('address')->nullable()->after('email');
            $table->unsignedTinyInteger('pax')->default(1)->after('address');

            // Extend category enum to include 'other'
            // MySQL: modify enum
            \DB::statement("ALTER TABLE guests MODIFY COLUMN category ENUM('family','friend','colleague','other') NOT NULL DEFAULT 'family'");
        });
    }

    public function down(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            $table->dropColumn(['phone', 'email', 'address', 'pax']);
            \DB::statement("ALTER TABLE guests MODIFY COLUMN category ENUM('family','friend','colleague') NOT NULL DEFAULT 'family'");
        });
    }
};
