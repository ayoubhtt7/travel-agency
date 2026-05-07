<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FlightBooking;
use App\Models\FlightPassenger;

class PaymentController extends Controller
{
    public function process(Request $request)
    {
        $request->validate([
            'card_number' => 'required|min:16',
            'expiry' => 'required',
            'cvv' => 'required|min:3',
        ]);

        // 🧪 SIMULATION RULE
        // Card 4242 = success, anything else = fail
        if ($request->card_number != '4242424242424242') {
            return redirect()->route('payment.fail');
        }

        $data = session('booking_data');

        if (!$data) {
            return redirect()->route('dashboard')->with('error', 'Session expired.');
        }

        // ✅ CREATE BOOKING
        $booking = FlightBooking::create($data);

        foreach ($data['passenger'] as $p) {
            FlightPassenger::create([
                'flight_booking_id' => $booking->id,
                'first_name' => $p['first_name'],
                'last_name' => $p['last_name'],
                'passport_number' => $p['passport_number'],
                'date_of_birth' => $p['date_of_birth'],
                'gender' => $p['gender'],
                'type' => $p['type'],
                'nationality' => $p['nationality'],
                'passport_expiry' => $p['passport_expiry'],
            ]);
        }

        return redirect()->route('payment.success');
    }

    public function success()
    {
        return redirect()->route('dashboard')
            ->with('success', '✅ Payment successful & booking confirmed!');
    }

    public function fail()
    {
        return redirect()->back()
            ->with('error', '❌ Payment failed. Try card 4242 4242 4242 4242');
    }
}