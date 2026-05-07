<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('flight_booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('flight_passenger_id')->constrained()->cascadeOnDelete();

            $table->string('ticket_code')->unique(); // 🎟 unique ticket code

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
