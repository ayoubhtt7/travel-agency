<?php

namespace App\Http\Controllers;

use App\Models\Airport;
use App\Models\Flight;
use App\Models\FlightBooking;
use App\Models\FlightPassenger;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\FlightTicketMail;

class FlightController extends Controller
{
    public function index()
    {
        $airports = Airport::orderBy('country_code')->orderBy('city')->get();
        return view('flights.index', compact('airports'));
    }

    public function search(Request $request)
    {
        // ✅ FIX: map frontend → DB values
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

        $outboundFlights = Flight::where('departure_airport_id', $departureAirport->id)
            ->where('arrival_airport_id', $arrivalAirport->id)
            ->where('class', $request->class)
            ->where('available_seats', '>=', $request->passengers)
            ->when($request->departure_date, function ($q) use ($request) {
                $q->whereBetween('departure_at', [
                    $request->departure_date . ' 00:00:00',
                    $request->departure_date . ' 23:59:59',
                ]);
            })
            ->get();

        return view('flights.results', compact(
            'outboundFlights',
            'departureAirport',
            'arrivalAirport',
            'request'
        ));
    }

    public function passengerForm(Flight $flight, Request $request)
    {
        $passengerCount = $request->passengers ?? 1;

        $returnFlight = null;

        if ($request->return_flight_id) {
            $returnFlight = Flight::find($request->return_flight_id);
        }

        $totalPrice = $flight->price * $passengerCount;

        return view('flights.passengers', compact(
            'flight',
            'returnFlight',
            'passengerCount',
            'totalPrice',
            'request'
        ));
    }

    public function book(Request $request)
    {
        $request->validate([
            'flight_id'         => 'required|exists:flights,id',
            'return_flight_id'  => 'nullable|exists:flights,id',
            'passengers'        => 'required|integer|min:1|max:9',
            'class'             => 'required|in:economy,business,first',
            'type'              => 'required|in:oneway,roundtrip',
            'payment_method'    => 'required|string',
            'passenger'         => 'required|array',
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

        // ✅ CREATE BOOKING
        $booking = FlightBooking::create([
            'user_id'          => auth()->id(),
            'flight_id'        => $flight->id,
            'return_flight_id' => $request->return_flight_id,
            'passengers'       => $request->passengers,
            'class'            => $request->class,
            'type'             => $request->type,
            'payment_method'   => $request->payment_method,
            'total_price'      => $total,
            'status'           => 'confirmed',
        ]);

        // ✅ SAVE PASSENGERS
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

        // ✅ UPDATE SEATS
        $flight->decrement('available_seats', $request->passengers);

        if ($returnFlight) {
            $returnFlight->decrement('available_seats', $request->passengers);
        }

        // ✅ LOAD RELATIONS
        $booking->load([
            'user',
            'flight.departureAirport',
            'flight.arrivalAirport'
        ]);

        // ✅ CREATE PDF
        $pdf = Pdf::loadView('pdf.flight-ticket', [
            'booking' => $booking
        ]);

        // ✅ CREATE FOLDER IF NOT EXISTS
        if (!file_exists(storage_path('app/public/tickets'))) {

            mkdir(storage_path('app/public/tickets'), 0777, true);
        }

        // ✅ SAVE PDF
        $pdfPath = storage_path(
            'app/public/tickets/ticket-' . $booking->id . '.pdf'
        );

        $pdf->save($pdfPath);

        // ✅ SEND EMAIL WITH PDF
        try {

            Mail::send(
                'emails.flight-ticket',
                ['booking' => $booking],
                function ($message) use ($booking, $pdfPath) {

                    $message->to($booking->user->email)
                        ->subject('✈ Your Flight Ticket')
                        ->attach($pdfPath);
                }
            );

        } catch (\Exception $e) {

            \Log::error($e->getMessage());
        }

        return redirect()->route('dashboard')
            ->with('success', 'Flight booked successfully! Ticket sent by email.');
    }
}