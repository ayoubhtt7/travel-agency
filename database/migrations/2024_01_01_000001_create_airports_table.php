<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('airports', function (Blueprint $table) {
            $table->id();
            $table->string('code', 3)->unique();   // IATA code e.g. ALG
            $table->string('name');                // Houari Boumediene
            $table->string('city');                // Alger
            $table->string('country');             // Algérie
            $table->string('country_code', 2);     // DZ
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('airports');
    }
};
