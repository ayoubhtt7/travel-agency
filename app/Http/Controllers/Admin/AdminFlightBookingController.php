<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FlightBooking;
use Illuminate\Http\Request;

class AdminFlightBookingController extends Controller
{
    public function index()
    {
        $bookings = FlightBooking::with(['user', 'flight.departureAirport', 'flight.arrivalAirport'])
            ->latest()
            ->paginate(20);

        return view('admin.flight_bookings.index', compact('bookings'));
    }

    public function show(FlightBooking $flightBooking)
    {
        $flightBooking->load([
            'user',
            'flight.departureAirport',
            'flight.arrivalAirport',
            'returnFlight.departureAirport',
            'returnFlight.arrivalAirport',
        ]);

        return view('admin.flight_bookings.show', compact('flightBooking'));
    }

    public function update(Request $request, FlightBooking $flightBooking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);

        $flightBooking->update(['status' => $request->status]);

        return back()->with('success', 'Réservation mise à jour.');
    }

    public function destroy(FlightBooking $flightBooking)
    {
        // Restore seats on delete
        $flightBooking->flight->increment('available_seats', $flightBooking->passengers);
        if ($flightBooking->returnFlight) {
            $flightBooking->returnFlight->increment('available_seats', $flightBooking->passengers);
        }

        $flightBooking->delete();

        return back()->with('success', 'Réservation supprimée.');
    }
}
