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
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('template_id')->constrained()->onDelete('restrict');
            $table->string('unique_url', 32)->unique()->nullable();
            $table->enum('status', ['draft', 'published', 'unpublished'])->default('draft');

            // Bride information
            $table->string('bride_name');
            $table->string('bride_father_name')->nullable();
            $table->string('bride_mother_name')->nullable();

            // Groom information
            $table->string('groom_name');
            $table->string('groom_father_name')->nullable();
            $table->string('groom_mother_name')->nullable();

            // Akad ceremony details
            $table->date('akad_date')->nullable();
            $table->time('akad_time_start')->nullable();
            $table->time('akad_time_end')->nullable();
            $table->string('akad_location')->nullable();

            // Reception details
            $table->date('reception_date')->nullable();
            $table->time('reception_time_start')->nullable();
            $table->time('reception_time_end')->nullable();
            $table->string('reception_location')->nullable();

            // Location details
            $table->text('full_address')->nullable();
            $table->string('google_maps_url')->nullable();

            // Media
            $table->string('music_url')->nullable();

            $table->timestamps();

            // Indexes for better query performance
            $table->index('user_id');
            $table->index('template_id');
            $table->index('status');
            $table->index('unique_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
