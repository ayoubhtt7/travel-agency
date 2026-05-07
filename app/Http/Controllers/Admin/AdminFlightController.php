<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use App\Models\Airport;
use Illuminate\Http\Request;

class AdminFlightController extends Controller
{
    public function index()
    {
        $flights = Flight::with([
                'departureAirport',
                'arrivalAirport'
            ])
            ->latest()
            ->paginate(20);

        return view('admin.flights.index', compact('flights'));
    }

    public function create()
    {
        $airports = Airport::orderBy('country_code')
            ->orderBy('city')
            ->get();

        return view('admin.flights.create', compact('airports'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([

            'departure_airport_id' => 'required|exists:airports,id',

            'arrival_airport_id' =>
                'required|exists:airports,id|different:departure_airport_id',

            'airline' => 'required|string|max:100',

            'flight_number' => 'required|string|max:20',

            'type' => 'required|in:oneway,roundtrip',

            'class' => 'required|in:economy,business,first',

            'departure_at' => 'required|date',

            'arrival_at' => 'required|date|after:departure_at',

            // ✅ RETURN DATES
            'return_departure_at' => 'nullable|date|after:arrival_at',
            'return_arrival_at'   => 'nullable|date|after:return_departure_at',

            'available_seats' => 'required|integer|min:1',

            'price' => 'required|numeric|min:0',

            'with_baggage' => 'nullable|boolean',

            'is_direct' => 'nullable|boolean',
        ]);

        $validated['with_baggage'] = $request->boolean('with_baggage');
        $validated['is_direct']    = $request->boolean('is_direct');

        Flight::create([

            'departure_airport_id' => $validated['departure_airport_id'],

            'arrival_airport_id' => $validated['arrival_airport_id'],

            'airline' => $validated['airline'],

            'flight_number' => $validated['flight_number'],

            'type' => $validated['type'],

            'class' => $validated['class'],

            'departure_at' => $validated['departure_at'],

            'arrival_at' => $validated['arrival_at'],

            // ✅ FIXED RETURN DATA
            'return_departure_at' => $validated['return_departure_at'] ?? null,
            'return_arrival_at'   => $validated['return_arrival_at'] ?? null,

            'available_seats' => $validated['available_seats'],

            'price' => $validated['price'],

            'with_baggage' => $validated['with_baggage'],

            'is_direct' => $validated['is_direct'],
        ]);

        return redirect()
            ->route('admin.flights.index')
            ->with('success', 'Flight created successfully.');
    }

    public function edit(Flight $flight)
    {
        $airports = Airport::orderBy('country_code')
            ->orderBy('city')
            ->get();

        return view(
            'admin.flights.edit',
            compact('flight', 'airports')
        );
    }

    public function update(Request $request, Flight $flight)
    {
        $validated = $request->validate([

            'departure_airport_id' => 'required|exists:airports,id',

            'arrival_airport_id' =>
                'required|exists:airports,id|different:departure_airport_id',

            'airline' => 'required|string|max:100',

            'flight_number' => 'required|string|max:20',

            'type' => 'required|in:oneway,roundtrip',

            'class' => 'required|in:economy,business,first',

            'departure_at' => 'required|date',

            'arrival_at' => 'required|date|after:departure_at',

            // ✅ RETURN DATES
            'return_departure_at' => 'nullable|date|after:arrival_at',
            'return_arrival_at'   => 'nullable|date|after:return_departure_at',

            'available_seats' => 'required|integer|min:0',

            'price' => 'required|numeric|min:0',

            'with_baggage' => 'nullable|boolean',

            'is_direct' => 'nullable|boolean',
        ]);

        $validated['with_baggage'] = $request->boolean('with_baggage');
        $validated['is_direct']    = $request->boolean('is_direct');

        $flight->update([

            'departure_airport_id' => $validated['departure_airport_id'],

            'arrival_airport_id' => $validated['arrival_airport_id'],

            'airline' => $validated['airline'],

            'flight_number' => $validated['flight_number'],

            'type' => $validated['type'],

            'class' => $validated['class'],

            'departure_at' => $validated['departure_at'],

            'arrival_at' => $validated['arrival_at'],

            // ✅ FIXED RETURN DATA
            'return_departure_at' => $validated['return_departure_at'] ?? null,
            'return_arrival_at'   => $validated['return_arrival_at'] ?? null,

            'available_seats' => $validated['available_seats'],

            'price' => $validated['price'],

            'with_baggage' => $validated['with_baggage'],

            'is_direct' => $validated['is_direct'],
        ]);

        return redirect()
            ->route('admin.flights.index')
            ->with('success', 'Flight updated successfully.');
    }

    public function destroy(Flight $flight)
    {
        $flight->delete();

        return back()->with(
            'success',
            'Flight deleted.'
        );
    }
}