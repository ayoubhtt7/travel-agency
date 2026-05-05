<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('car_rentals', function (Blueprint $table) {
            $table->id();
            $table->string('brand');
            $table->string('model');
            $table->enum('type', ['economy', 'compact', 'suv', 'luxury', 'van', 'convertible']);
            $table->unsignedTinyInteger('seats')->default(5);
            $table->enum('transmission', ['manual', 'automatic'])->default('automatic');
            $table->enum('fuel', ['petrol', 'diesel', 'electric', 'hybrid'])->default('petrol');
            $table->boolean('with_ac')->default(true);
            $table->boolean('unlimited_mileage')->default(false);
            $table->string('image')->nullable();
            $table->decimal('price_per_day', 10, 2);
            $table->unsignedInteger('available_units')->default(1);
            $table->foreignId('destination_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_rentals');
    }
};
