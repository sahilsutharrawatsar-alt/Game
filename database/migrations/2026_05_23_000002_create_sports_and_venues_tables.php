<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sports', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('venues', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('address');
            $table->string('city')->index();
            $table->string('state')->default('Punjab');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->decimal('base_price', 10, 2)->default(0);
            $table->time('opening_time')->default('06:00:00');
            $table->time('closing_time')->default('23:00:00');
            $table->string('status')->default('active')->index();
            $table->boolean('is_featured')->default(false)->index();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->timestamps();
            $table->index(['city', 'status']);
        });

        Schema::create('sport_venue', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sport_id')->constrained()->cascadeOnDelete();
            $table->foreignId('venue_id')->constrained()->cascadeOnDelete();
            $table->decimal('price_per_hour', 10, 2);
            $table->timestamps();
            $table->unique(['sport_id', 'venue_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sport_venue');
        Schema::dropIfExists('venues');
        Schema::dropIfExists('sports');
    }
};
