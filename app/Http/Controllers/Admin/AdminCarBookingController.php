<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarBooking;
use Illuminate\Http\Request;

class AdminCarBookingController extends Controller
{
    public function index()
    {
        $bookings = CarBooking::with(['user', 'car', 'tripBooking'])
            ->latest()
            ->paginate(20);

        return view('admin.car-bookings.index', compact('bookings'));
    }

    public function show(CarBooking $carBooking)
    {
        $carBooking->load(['user', 'car.destination', 'tripBooking.trip']);
        return view('admin.car-bookings.show', compact('carBooking'));
    }

    public function update(Request $request, CarBooking $carBooking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);

        $carBooking->update(['status' => $request->status]);

        return back()->with('success', 'Booking status updated.');
    }

    public function destroy(CarBooking $carBooking)
    {
        // Restore unit if not already cancelled
        if ($carBooking->status !== 'cancelled') {
            $carBooking->car->increment('available_units');
        }
        $carBooking->delete();
        return back()->with('success', 'Car booking deleted.');
    }
}
