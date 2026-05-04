<?php

namespace App\Http\Controllers;

use App\Models\CarBooking;
use App\Models\CarRental;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CarRentalController extends Controller
{
    public function index(Request $request)
    {
        $destinations = Destination::orderBy('name')->get();

        $cars = CarRental::with('destination')
            ->when($request->destination_id, fn($q) => $q->where('destination_id', $request->destination_id))
            ->when($request->type, fn($q) => $q->where('type', $request->type))
            ->when($request->transmission, fn($q) => $q->where('transmission', $request->transmission))
            ->when($request->max_price, fn($q) => $q->where('price_per_day', '<=', $request->max_price))
            ->where('available_units', '>', 0)
            ->orderBy('price_per_day')
            ->get();

        return view('cars.index', compact('cars', 'destinations'));
    }

    public function show(CarRental $carRental)
    {
        return view('cars.show', ['car' => $carRental->load('destination')]);
    }

    public function book(Request $request)
    {
        $request->validate([
            'car_rental_id'   => 'required|exists:car_rentals,id',
            'pickup_date'     => 'required|date|after_or_equal:today',
            'return_date'     => 'required|date|after:pickup_date',
            'pickup_location' => 'required|string|max:255',
            'booking_id'      => 'nullable|exists:bookings,id',
        ]);

        $car  = CarRental::findOrFail($request->car_rental_id);

        if ($car->available_units < 1) {
            return back()->with('error', 'Sorry, this car is no longer available.');
        }

        $days  = now()->parse($request->pickup_date)->diffInDays($request->return_date);
        $total = $car->price_per_day * $days;

        DB::transaction(function () use ($request, $car, $total) {
            CarBooking::create([
                'user_id'         => auth()->id(),
                'car_rental_id'   => $car->id,
                'booking_id'      => $request->booking_id,
                'pickup_date'     => $request->pickup_date,
                'return_date'     => $request->return_date,
                'pickup_location' => $request->pickup_location,
                'total_price'     => $total,
                'status'          => 'pending',
            ]);
            $car->decrement('available_units');
        });

        $redirect = $request->booking_id
            ? route('booking.addons', $request->booking_id)
            : route('dashboard');

        return redirect($redirect)->with('success', 'Car rental booked successfully!');
    }

    public function destroy(CarBooking $carBooking)
    {
        abort_if($carBooking->user_id !== auth()->id(), 403);

        DB::transaction(function () use ($carBooking) {
            $carBooking->car->increment('available_units');
            $carBooking->delete();
        });

        return back()->with('success', 'Car booking cancelled.');
    }
}
