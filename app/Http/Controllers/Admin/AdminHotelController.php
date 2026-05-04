<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\HotelRoom;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminHotelController extends Controller
{
    public function index()
    {
        $hotels = Hotel::with('destination')->withCount('rooms')->latest()->paginate(20);
        return view('admin.hotels.index', compact('hotels'));
    }

    public function create()
    {
        $destinations = Destination::orderBy('name')->get();
        return view('admin.hotels.create', compact('destinations'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:150',
            'stars'          => 'required|integer|min:1|max:5',
            'address'        => 'required|string|max:255',
            'destination_id' => 'nullable|exists:destinations,id',
            'description'    => 'nullable|string',
            'amenities'      => 'nullable|array',
            'amenities.*'    => 'string',
            'image'          => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('hotels', 'public');
        }

        $hotel = Hotel::create($data);

        // Handle rooms submitted inline
        if ($request->has('rooms')) {
            foreach ($request->rooms as $room) {
                HotelRoom::create([
                    'hotel_id'        => $hotel->id,
                    'type'            => $room['type'],
                    'capacity'        => $room['capacity'],
                    'price_per_night' => $room['price_per_night'],
                    'available_rooms' => $room['available_rooms'],
                    'with_breakfast'  => isset($room['with_breakfast']),
                    'refundable'      => isset($room['refundable']),
                ]);
            }
        }

        return redirect()->route('admin.hotels.index')->with('success', 'Hotel created.');
    }

    public function show(Hotel $hotel)
    {
        $hotel->load(['rooms', 'destination']);
        return view('admin.hotels.show', compact('hotel'));
    }

    public function edit(Hotel $hotel)
    {
        $destinations = Destination::orderBy('name')->get();
        $hotel->load('rooms');
        return view('admin.hotels.edit', compact('hotel', 'destinations'));
    }

    public function update(Request $request, Hotel $hotel)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:150',
            'stars'          => 'required|integer|min:1|max:5',
            'address'        => 'required|string|max:255',
            'destination_id' => 'nullable|exists:destinations,id',
            'description'    => 'nullable|string',
            'amenities'      => 'nullable|array',
            'amenities.*'    => 'string',
            'image'          => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($hotel->image) Storage::disk('public')->delete($hotel->image);
            $data['image'] = $request->file('image')->store('hotels', 'public');
        }

        $hotel->update($data);

        return redirect()->route('admin.hotels.index')->with('success', 'Hotel updated.');
    }

    public function destroy(Hotel $hotel)
    {
        if ($hotel->image) Storage::disk('public')->delete($hotel->image);
        $hotel->delete();
        return back()->with('success', 'Hotel deleted.');
    }

    // Manage rooms separately
    public function storeRoom(Request $request, Hotel $hotel)
    {
        $data = $request->validate([
            'type'            => 'required|in:single,double,twin,suite,family',
            'capacity'        => 'required|integer|min:1',
            'price_per_night' => 'required|numeric|min:0',
            'available_rooms' => 'required|integer|min:0',
            'with_breakfast'  => 'boolean',
            'refundable'      => 'boolean',
            'image'           => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('hotel-rooms', 'public');
        }

        $data['with_breakfast'] = $request->boolean('with_breakfast');
        $data['refundable']     = $request->boolean('refundable');
        $data['hotel_id']       = $hotel->id;

        HotelRoom::create($data);

        return back()->with('success', 'Room added.');
    }

    public function destroyRoom(HotelRoom $room)
    {
        if ($room->image) Storage::disk('public')->delete($room->image);
        $room->delete();
        return back()->with('success', 'Room deleted.');
    }
}
