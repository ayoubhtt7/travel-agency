<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flight_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('flight_id')->constrained()->onDelete('cascade');
            $table->foreignId('return_flight_id')->nullable()->constrained('flights')->onDelete('set null');
            $table->integer('passengers');
            $table->enum('class', ['economique', 'eco_premium', 'affaires', 'premiere'])->default('economique');
            $table->enum('type', ['aller_simple', 'aller_retour'])->default('aller_simple');
            $table->decimal('total_price', 12, 2);
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flight_bookings');
    }
};
