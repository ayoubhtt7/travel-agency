<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Trip;
use App\Models\Booking;
use App\Models\Destination;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $users = User::count();
        $trips = Trip::count();
        $bookings = Booking::count();
        $destinations = Destination::count();

        return view('admin.dashboard.index', compact(
            'users',
            'trips',
            'bookings',
            'destinations'
        ));
    }
}