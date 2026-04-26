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
    Schema::table('trips', function (Blueprint $table) {
        $table->integer('duration')->nullable();
        $table->integer('available_seats')->nullable();
        $table->dateTime('start_date')->nullable();
        $table->dateTime('end_date')->nullable();
        $table->foreignId('destination_id')->constrained()->cascadeOnDelete();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            //
        });
    }
};
