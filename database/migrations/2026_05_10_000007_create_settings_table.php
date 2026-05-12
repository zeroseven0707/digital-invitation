<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string|boolean|integer|json|secret
            $table->string('group')->default('general'); // general|midtrans|payment|app
            $table->string('label');                    // label untuk admin UI
            $table->text('description')->nullable();
            $table->boolean('is_public')->default(false); // apakah bisa diakses tanpa auth
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
