<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'flight_booking_id',
        'flight_passenger_id',
        'ticket_code',
    ];

    // ✅ Booking relation
    public function booking()
    {
        return $this->belongsTo(FlightBooking::class, 'flight_booking_id');
    }

    // ✅ Passenger relation
    public function passenger()
    {
        return $this->belongsTo(
            FlightPassenger::class,
            'flight_passenger_id'
        );
    }
}