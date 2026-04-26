<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    protected $fillable = ['code', 'name', 'city', 'country', 'country_code'];

    public function departureFlights()
    {
        return $this->hasMany(Flight::class, 'departure_airport_id');
    }

    public function arrivalFlights()
    {
        return $this->hasMany(Flight::class, 'arrival_airport_id');
    }

    // e.g. "Alger (ALG)"
    public function getLabelAttribute(): string
    {
        return "{$this->city} – {$this->name} ({$this->code})";
    }
}
