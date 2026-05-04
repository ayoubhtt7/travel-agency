<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HotelBooking;
use Illuminate\Http\Request;

class AdminHotelBookingController extends Controller
{
    public function index()
    {
        $bookings = HotelBooking::with(['user', 'hotel', 'room', 'tripBooking'])
            ->latest()
            ->paginate(20);

        return view('admin.hotel-bookings.index', compact('bookings'));
    }

    public function show(HotelBooking $hotelBooking)
    {
        $hotelBooking->load(['user', 'hotel.destination', 'room', 'tripBooking.trip']);
        return view('admin.hotel-bookings.show', compact('hotelBooking'));
    }

    public function update(Request $request, HotelBooking $hotelBooking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);

        $hotelBooking->update(['status' => $request->status]);

        return back()->with('success', 'Booking status updated.');
    }

    public function destroy(HotelBooking $hotelBooking)
    {
        if ($hotelBooking->status !== 'cancelled') {
            $hotelBooking->room->increment('available_rooms');
        }
        $hotelBooking->delete();
        return back()->with('success', 'Hotel booking deleted.');
    }
}
