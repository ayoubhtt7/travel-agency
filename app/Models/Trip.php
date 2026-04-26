<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    protected $fillable = [
        'title',
        'description',
        'price',
        'duration',
        'available_seats',
        'start_date',
        'end_date',
        'destination_id',
        'image',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date'   => 'date',
            'price'      => 'decimal:2',
        ];
    }

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
