<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlightPassenger extends Model
{
    protected $fillable = [
        'flight_booking_id',
        'first_name',
        'last_name',
        'passport_number',
        'date_of_birth',
        'gender',
        'type',
        'nationality',
        'passport_expiry',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth'   => 'date',
            'passport_expiry' => 'date',
        ];
    }

    public function booking()
    {
        return $this->belongsTo(FlightBooking::class, 'flight_booking_id');
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
