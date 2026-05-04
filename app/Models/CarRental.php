<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarRental extends Model
{
    protected $fillable = [
        'brand',
        'model',
        'type',
        'seats',
        'transmission',
        'fuel',
        'with_ac',
        'unlimited_mileage',
        'image',
        'price_per_day',
        'available_units',
        'destination_id',
    ];

    protected function casts(): array
    {
        return [
            'with_ac'           => 'boolean',
            'unlimited_mileage' => 'boolean',
            'price_per_day'     => 'decimal:2',
        ];
    }

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function bookings()
    {
        return $this->hasMany(CarBooking::class);
    }

    public function getLabelAttribute(): string
    {
        return "{$this->brand} {$this->model} (" . ucfirst($this->type) . ")";
    }
}
