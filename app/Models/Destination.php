<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    protected $fillable = ['name', 'country', 'description', 'image'];

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }
}
