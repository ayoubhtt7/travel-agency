<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['user', 'trip'])->latest()->paginate(20);
        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'trip.destination']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);

        $booking->update(['status' => $request->status]);

        return back()->with('success', 'Booking updated successfully.');
    }

    public function destroy(Booking $booking)
    {
        $booking->trip->increment('available_seats', $booking->number_of_persons);
        $booking->delete();

        return back()->with('success', 'Booking deleted.');
    }
}
