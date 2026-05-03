<?php

namespace App\Http\Controllers;

use App\Models\Airport;
use App\Models\Flight;
use App\Models\FlightBooking;
use App\Models\FlightPassenger;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    public function index()
    {
        $airports = Airport::orderBy('country_code')->orderBy('city')->get();
        return view('flights.index', compact('airports'));
    }

    public function search(Request $request)
    {
        // ✅ FIX mapp
        $classMap = [
            'economique' => 'economy',
            'eco_premium' => 'business',
            'affaires' => 'business',
            'premiere' => 'first',
        ];

        $typeMap = [
            'aller_simple' => 'oneway',
            'aller_retour' => 'roundtrip',
        ];

        $request->merge([
            'class' => $classMap[$request->class] ?? $request->class,
            'type'  => $typeMap[$request->type] ?? $request->type,
        ]);

        $request->validate([
            'departure_code' => 'required|exists:airports,code',
            'arrival_code'   => 'required|exists:airports,code|different:departure_code',
            'departure_date' => 'nullable|date',
            'return_date'    => 'nullable|date',
            'passengers'     => 'required|integer|min:1|max:9',
            'class'          => 'required|in:economy,business,first',
            'type'           => 'required|in:oneway,roundtrip',
        ]);

        $departureAirport = Airport::where('code', $request->departure_code)->first();
        $arrivalAirport   = Airport::where('code', $request->arrival_code)->first();

        $outboundQuery = Flight::where('departure_airport_id', $departureAirport->id)
            ->where('arrival_airport_id', $arrivalAirport->id)
            ->where('class', $request->class)
            ->where('available_seats', '>=', $request->passengers);

        if ($request->filled('departure_date')) {
            $outboundQuery->whereBetween('departure_at', [
                $request->departure_date . ' 00:00:00',
                $request->departure_date . ' 23:59:59',
            ]);
        }

        $outboundFlights = $outboundQuery->get();

        return view('flights.results', compact(
            'outboundFlights',
            'departureAirport',
            'arrivalAirport',
            'request'
        ));
    }

    public function passengerForm(Request $request)
    {
        $request->validate([
            'flight_id'        => 'required|exists:flights,id',
            'return_flight_id' => 'nullable|exists:flights,id',
            'passengers'       => 'required|integer|min:1|max:9',
            'class'            => 'required|in:economique,eco_premium,affaires,premiere',
            'type'             => 'required|in:aller_simple,aller_retour',
        ]);

        $flight       = Flight::with(['departureAirport', 'arrivalAirport'])->findOrFail($request->flight_id);
        $returnFlight = $request->return_flight_id
            ? Flight::with(['departureAirport', 'arrivalAirport'])->findOrFail($request->return_flight_id)
            : null;

        $passengerCount = (int) $request->passengers;
        $totalPrice     = $flight->price * $passengerCount;
        if ($returnFlight) $totalPrice += $returnFlight->price * $passengerCount;

        return view('flights.passengers', compact(
            'flight', 'returnFlight', 'request', 'passengerCount', 'totalPrice'
        ));
    }

    public function book(Request $request)
    {
        $request->validate([
            'flight_id'                   => 'required|exists:flights,id',
            'return_flight_id'            => 'nullable|exists:flights,id',
            'passengers'                  => 'required|integer|min:1|max:9',
            'class'                       => 'required|in:economique,eco_premium,affaires,premiere',
            'type'                        => 'required|in:aller_simple,aller_retour',
            'passenger'                   => 'required|array',
            'passenger.*.first_name'      => 'required|string|max:100',
            'passenger.*.last_name'       => 'required|string|max:100',
            'passenger.*.passport_number' => 'required|string|max:50',
            'passenger.*.date_of_birth'   => 'required|date|before:today',
            'passenger.*.gender'          => 'required|in:male,female',
            'passenger.*.type'            => 'required|in:adult,child,infant',
            'passenger.*.nationality'     => 'required|string|max:100',
            'passenger.*.passport_expiry' => 'required|date|after:today',
        ]);

        $flight = Flight::findOrFail($request->flight_id);

        if ($flight->available_seats < $request->passengers) {
            return back()->with('error', 'Not enough seats available.');
        }

        $total = $flight->price * $request->passengers;

        $returnFlight = null;
        if ($request->return_flight_id) {
            $returnFlight = Flight::findOrFail($request->return_flight_id);
            $total += $returnFlight->price * $request->passengers;
        }

        $booking = FlightBooking::create([
            'user_id'          => auth()->id(),
            'flight_id'        => $flight->id,
            'return_flight_id' => $request->return_flight_id,
            'passengers'       => $request->passengers,
            'class'            => $request->class,
            'type'             => $request->type,
            'total_price'      => $total,
            'status'           => 'confirmed',
        ]);

        foreach ($request->passenger as $p) {
            FlightPassenger::create([
                'flight_booking_id' => $booking->id,
                'first_name'        => $p['first_name'],
                'last_name'         => $p['last_name'],
                'passport_number'   => $p['passport_number'],
                'date_of_birth'     => $p['date_of_birth'],
                'gender'            => $p['gender'],
                'type'              => $p['type'],
                'nationality'       => $p['nationality'],
                'passport_expiry'   => $p['passport_expiry'],
            ]);
        }

        $flight->decrement('available_seats', $request->passengers);
        if ($returnFlight) $returnFlight->decrement('available_seats', $request->passengers);

        return redirect()->route('dashboard')
            ->with('success', 'Booking confirmed!');
    }
}
