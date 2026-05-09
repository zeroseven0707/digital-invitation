<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            // Check-out timestamp
            $table->timestamp('checked_out_at')->nullable()->after('souvenir_taken_at');

            // Second souvenir scan (for events with 2 separate souvenirs)
            $table->timestamp('souvenir2_taken_at')->nullable()->after('checked_out_at');
        });
    }

    public function down(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            $table->dropColumn(['checked_out_at', 'souvenir2_taken_at']);
        });
    }
};
