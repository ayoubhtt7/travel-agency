<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('flights', function (Blueprint $table) {

            // ✅ only add if missing

            if (!Schema::hasColumn('flights', 'return_departure_at')) {

                $table->dateTime('return_departure_at')
                    ->nullable()
                    ->after('arrival_at');
            }

            if (!Schema::hasColumn('flights', 'return_arrival_at')) {

                $table->dateTime('return_arrival_at')
                    ->nullable()
                    ->after('return_departure_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('flights', function (Blueprint $table) {

            if (Schema::hasColumn('flights', 'return_departure_at')) {
                $table->dropColumn('return_departure_at');
            }

            if (Schema::hasColumn('flights', 'return_arrival_at')) {
                $table->dropColumn('return_arrival_at');
            }
        });
    }
};