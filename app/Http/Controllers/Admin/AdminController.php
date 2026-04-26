<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\User;
use App\Models\Booking;
use App\Models\Destination;

class AdminController extends Controller
{
    public function index()
    {
        $recentBookings = Booking::with(['user', 'trip'])->latest()->take(5)->get();

        return view('admin.dashboard', [
            'trips'          => Trip::count(),
            'users'          => User::count(),
            'bookings'       => Booking::count(),
            'destinations'   => Destination::count(),
            'revenue'        => Booking::where('status', 'confirmed')->sum('total_price'),
            'recentBookings' => $recentBookings,
        ]);
    }
}
