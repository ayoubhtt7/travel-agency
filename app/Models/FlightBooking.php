<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlightBooking extends Model
{
    protected $fillable = [
        'user_id',
        'flight_id',
        'return_flight_id',
        'passengers',
        'class',
        'type',
        'total_price',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function flight()
    {
        return $this->belongsTo(Flight::class);
    }

    public function returnFlight()
    {
        return $this->belongsTo(Flight::class, 'return_flight_id');
    }

    public function passengerDetails()
    {
        return $this->hasMany(FlightPassenger::class);
    }
}
