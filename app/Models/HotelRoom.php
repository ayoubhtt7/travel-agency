<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelRoom extends Model
{
    protected $fillable = [
        'hotel_id',
        'type',
        'capacity',
        'price_per_night',
        'available_rooms',
        'with_breakfast',
        'refundable',
        'image',
    ];

    protected function casts(): array
    {
        return [
            'with_breakfast'  => 'boolean',
            'refundable'      => 'boolean',
            'price_per_night' => 'decimal:2',
        ];
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function bookings()
    {
        return $this->hasMany(HotelBooking::class);
    }
}
