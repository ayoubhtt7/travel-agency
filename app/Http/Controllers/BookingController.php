<?php

namespace App\Http\Controllers;

use App\Mail\BookingConfirmed;
use App\Mail\BookingCancelled;
use App\Models\Booking;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = auth()->user()->bookings()->with('trip')->latest()->get();
        return view('bookings.index', compact('bookings'));
    }

    public function create($trip_id)
    {
        $trip = Trip::findOrFail($trip_id);
        return view('bookings.create', compact('trip'));
    }

    public function store(Request $request, $trip_id)
    {
        $trip = Trip::findOrFail($trip_id);

        $request->validate([
            'number_of_persons' => 'required|integer|min:1|max:' . $trip->available_seats,
        ]);

        if ($request->number_of_persons > $trip->available_seats) {
            return back()->with('error', 'Not enough seats available.');
        }

        $total = $trip->price * $request->number_of_persons;

        $booking = Booking::create([
            'user_id'           => auth()->id(),
            'trip_id'           => $trip->id,
            'number_of_persons' => $request->number_of_persons,
            'total_price'       => $total,
            'status'            => 'pending',
        ]);

        $trip->decrement('available_seats', $request->number_of_persons);

        // ✉️ Confirmation email
        Mail::to($booking->user->email)
            ->send(new BookingConfirmed($booking->load('trip.destination', 'user')));

        return redirect()->route('trips.index')
            ->with('success', 'Booking successful! A confirmation email has been sent.');
    }

    public function destroy(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        // Load relationships needed for the email before deleting
        $booking->load('trip.destination', 'user');

        $booking->trip->increment('available_seats', $booking->number_of_persons);

        // ✉️ Cancellation email
        Mail::to($booking->user->email)
            ->send(new BookingCancelled($booking));

        $booking->delete();

        return back()->with('success', 'Booking cancelled. A confirmation email has been sent.');
    }
}
