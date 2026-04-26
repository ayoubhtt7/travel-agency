<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('departure_airport_id')->constrained('airports')->onDelete('cascade');
            $table->foreignId('arrival_airport_id')->constrained('airports')->onDelete('cascade');
            $table->string('airline');
            $table->string('flight_number');
            $table->enum('type', ['aller_simple', 'aller_retour', 'direct'])->default('aller_simple');
            $table->enum('class', ['economique', 'eco_premium', 'affaires', 'premiere'])->default('economique');
            $table->dateTime('departure_at');
            $table->dateTime('arrival_at');
            $table->integer('available_seats')->default(0);
            $table->decimal('price', 12, 2);
            $table->boolean('with_baggage')->default(false);
            $table->boolean('is_direct')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
