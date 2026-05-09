<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Trip bookings
        $tripBookings = $user->bookings()
            ->with('trip')
            ->latest()
            ->get();

        // Hotel bookings
        $hotelBookings = $user->hotelBookings()
            ->with('hotel')
            ->latest()
            ->get();

        // Car rentals
        $carRentals = $user->carRentals()
            ->with('car')
            ->latest()
            ->get();

        // Stats
        $totalTrips = $tripBookings->count();

        $totalSpent =
            $tripBookings->sum('total_price') +
            $hotelBookings->sum('total_price') +
            $carRentals->sum('total_price');

        return view('user.dashboard', compact(
            'tripBookings',
            'hotelBookings',
            'carRentals',
            'totalTrips',
            'totalSpent'
        ));
    }
}