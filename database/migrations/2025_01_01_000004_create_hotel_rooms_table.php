<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hotel_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['single', 'double', 'twin', 'suite', 'family']);
            $table->unsignedSmallInteger('capacity')->default(2);
            $table->decimal('price_per_night', 10, 2);
            $table->unsignedInteger('available_rooms')->default(1);
            $table->boolean('with_breakfast')->default(false);
            $table->boolean('refundable')->default(true);
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotel_rooms');
    }
};
