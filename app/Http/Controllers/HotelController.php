<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\HotelBooking;
use App\Models\HotelRoom;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HotelController extends Controller
{
    public function index(Request $request)
    {
        $destinations = Destination::orderBy('name')->get();

        $hotels = Hotel::with(['destination', 'rooms'])
            ->when($request->destination_id, fn($q) => $q->where('destination_id', $request->destination_id))
            ->when($request->stars, fn($q) => $q->where('stars', $request->stars))
            ->when($request->amenity, fn($q) => $q->whereJsonContains('amenities', $request->amenity))
            ->orderByDesc('stars')
            ->get();

        return view('hotels.index', compact('hotels', 'destinations'));
    }

    public function show(Hotel $hotel)
    {
        $hotel->load(['rooms', 'destination']);
        return view('hotels.show', compact('hotel'));
    }

    public function book(Request $request)
    {
        $request->validate([
            'hotel_room_id' => 'required|exists:hotel_rooms,id',
            'check_in'      => 'required|date|after_or_equal:today',
            'check_out'     => 'required|date|after:check_in',
            'guests'        => 'required|integer|min:1',
            'booking_id'    => 'nullable|exists:bookings,id',
        ]);

        $room = HotelRoom::with('hotel')->findOrFail($request->hotel_room_id);

        if ($request->guests > $room->capacity) {
            return back()->with('error', "This room type fits a maximum of {$room->capacity} guests.");
        }

        if ($room->available_rooms < 1) {
            return back()->with('error', 'Sorry, no rooms of this type are available.');
        }

        $nights = now()->parse($request->check_in)->diffInDays($request->check_out);
        $total  = $room->price_per_night * $nights;

        DB::transaction(function () use ($request, $room, $total) {
            HotelBooking::create([
                'user_id'       => auth()->id(),
                'hotel_id'      => $room->hotel_id,
                'hotel_room_id' => $room->id,
                'booking_id'    => $request->booking_id,
                'check_in'      => $request->check_in,
                'check_out'     => $request->check_out,
                'guests'        => $request->guests,
                'total_price'   => $total,
                'status'        => 'pending',
            ]);
            $room->decrement('available_rooms');
        });

        $redirect = $request->booking_id
            ? route('booking.addons', $request->booking_id)
            : route('dashboard');

        return redirect($redirect)->with('success', 'Hotel booked successfully!');
    }

    public function destroy(HotelBooking $hotelBooking)
    {
        abort_if($hotelBooking->user_id !== auth()->id(), 403);

        DB::transaction(function () use ($hotelBooking) {
            $hotelBooking->room->increment('available_rooms');
            $hotelBooking->delete();
        });

        return back()->with('success', 'Hotel booking cancelled.');
    }
}
