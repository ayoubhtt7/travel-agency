<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flight_passengers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flight_booking_id')
                  ->constrained('flight_bookings')
                  ->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('passport_number');
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female']);
            $table->enum('type', ['adult', 'child', 'infant'])->default('adult');
            $table->string('nationality');
            $table->date('passport_expiry');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flight_passengers');
    }
};
