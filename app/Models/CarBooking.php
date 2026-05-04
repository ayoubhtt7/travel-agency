<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarBooking extends Model
{
    protected $fillable = [
        'user_id',
        'car_rental_id',
        'booking_id',
        'pickup_date',
        'return_date',
        'pickup_location',
        'total_price',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'pickup_date' => 'date',
            'return_date' => 'date',
            'total_price' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function car()
    {
        return $this->belongsTo(CarRental::class, 'car_rental_id');
    }

    public function tripBooking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function getDaysAttribute(): int
    {
        return $this->pickup_date->diffInDays($this->return_date);
    }
}
