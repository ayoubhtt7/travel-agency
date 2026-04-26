<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    protected $fillable = [
        'departure_airport_id',
        'arrival_airport_id',
        'airline',
        'flight_number',
        'type',
        'class',
        'departure_at',
        'arrival_at',
        'available_seats',
        'price',
        'with_baggage',
        'is_direct',
    ];

    protected function casts(): array
    {
        return [
            'departure_at' => 'datetime',
            'arrival_at'   => 'datetime',
            'price'        => 'decimal:2',
            'with_baggage' => 'boolean',
            'is_direct'    => 'boolean',
        ];
    }

    public function departureAirport()
    {
        return $this->belongsTo(Airport::class, 'departure_airport_id');
    }

    public function arrivalAirport()
    {
        return $this->belongsTo(Airport::class, 'arrival_airport_id');
    }

    public function bookings()
    {
        return $this->hasMany(FlightBooking::class);
    }

    // Duration in hours and minutes
    public function getDurationAttribute(): string
    {
        $minutes = $this->departure_at->diffInMinutes($this->arrival_at);
        return floor($minutes / 60) . 'h ' . ($minutes % 60) . 'min';
    }
}
