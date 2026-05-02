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
        $flights = Flight::with(['departureAirport', 'arrivalAirport'])
            ->latest()
            ->paginate(20);

        return view('admin.flights.index', compact('flights'));
    }

    public function create()
    {
        $airports = Airport::orderBy('country_code')->orderBy('city')->get();
        return view('admin.flights.create', compact('airports'));
    }

    public function store(Request $request)
    {
        $rules = [
            'departure_airport_id' => 'required|exists:airports,id',
            'arrival_airport_id'   => 'required|exists:airports,id|different:departure_airport_id',
            'airline'              => 'required|string|max:100',
            'flight_number'        => 'required|string|max:20',
            'type'                 => 'required|in:aller_simple,aller_retour,direct',
            'class'                => 'required|in:economique,eco_premium,affaires,premiere',
            'departure_at'         => 'required|date',
            'arrival_at'           => 'required|date|after:departure_at',
            'available_seats'      => 'required|integer|min:1',
            'price'                => 'required|numeric|min:0',
            'with_baggage'         => 'nullable|boolean',
            'is_direct'            => 'nullable|boolean',
        ];

        if ($request->type === 'aller_retour') {
            $rules['return_departure_at']  = 'required|date|after:arrival_at';
            $rules['return_arrival_at']    = 'required|date|after:return_departure_at';
            $rules['return_flight_number'] = 'nullable|string|max:20';
        }

        $validated    = $request->validate($rules);
        $withBaggage  = $request->boolean('with_baggage');
        $isDirect     = $request->boolean('is_direct');

        Flight::create([
            'departure_airport_id' => $validated['departure_airport_id'],
            'arrival_airport_id'   => $validated['arrival_airport_id'],
            'airline'              => $validated['airline'],
            'flight_number'        => $validated['flight_number'],
            'type'                 => $validated['type'],
            'class'                => $validated['class'],
            'departure_at'         => $validated['departure_at'],
            'arrival_at'           => $validated['arrival_at'],
            'available_seats'      => $validated['available_seats'],
            'price'                => $validated['price'],
            'with_baggage'         => $withBaggage,
            'is_direct'            => $isDirect,
        ]);

        if ($request->type === 'aller_retour') {
            Flight::create([
                'departure_airport_id' => $validated['arrival_airport_id'],
                'arrival_airport_id'   => $validated['departure_airport_id'],
                'airline'              => $validated['airline'],
                'flight_number'        => $request->filled('return_flight_number')
                    ? $request->return_flight_number
                    : $validated['flight_number'],
                'type'                 => 'aller_retour',
                'class'                => $validated['class'],
                'departure_at'         => $validated['return_departure_at'],
                'arrival_at'           => $validated['return_arrival_at'],
                'available_seats'      => $validated['available_seats'],
                'price'                => $validated['price'],
                'with_baggage'         => $withBaggage,
                'is_direct'            => $isDirect,
            ]);

            return redirect()->route('admin.flights.index')
                ->with('success', 'Round trip created — 2 flights added.');
        }

        return redirect()->route('admin.flights.index')
            ->with('success', 'Flight created successfully.');
    }

    public function edit(Flight $flight)
    {
        $airports = Airport::orderBy('country_code')->orderBy('city')->get();
        return view('admin.flights.edit', compact('flight', 'airports'));
    }

    public function update(Request $request, Flight $flight)
    {
        $validated = $request->validate([
            'departure_airport_id' => 'required|exists:airports,id',
            'arrival_airport_id'   => 'required|exists:airports,id|different:departure_airport_id',
            'airline'              => 'required|string|max:100',
            'flight_number'        => 'required|string|max:20',
            'type'                 => 'required|in:aller_simple,aller_retour,direct',
            'class'                => 'required|in:economique,eco_premium,affaires,premiere',
            'departure_at'         => 'required|date',
            'arrival_at'           => 'required|date|after:departure_at',
            'available_seats'      => 'required|integer|min:0',
            'price'                => 'required|numeric|min:0',
            'with_baggage'         => 'nullable|boolean',
            'is_direct'            => 'nullable|boolean',
        ]);

        $validated['with_baggage'] = $request->boolean('with_baggage');
        $validated['is_direct']    = $request->boolean('is_direct');

        $flight->update($validated);

        return redirect()->route('admin.flights.index')
            ->with('success', 'Flight updated successfully.');
    }

    public function destroy(Flight $flight)
    {
        $flight->delete();
        return back()->with('success', 'Flight deleted.');
    }
}