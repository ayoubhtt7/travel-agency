<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('trips', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('description');
    $table->decimal('price', 8, 2);
    //$table->integer('duration');
    //$table->integer('available_seats');
    //$table->dateTime('start_date');
    //$table->dateTime('end_date');

    // $table->foreignId('destination_id')
        //  ->constrained()
        //  ->cascadeOnDelete();

    $table->timestamps();
});
    }

    public function down()
    {
        Schema::dropIfExists('trips');
    }
};

