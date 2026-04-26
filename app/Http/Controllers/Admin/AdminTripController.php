<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\Destination;
use Illuminate\Http\Request;

class AdminTripController extends Controller
{
    public function index()
    {
        $trips = Trip::with('destination')->latest()->get();
        return view('admin.trips.index', compact('trips'));
    }

    public function create()
    {
        $destinations = Destination::orderBy('name')->get();
        return view('admin.trips.create', compact('destinations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'           => 'required|string|max:255',
            'description'     => 'required|string',
            'price'           => 'required|numeric|min:0',
            'duration'        => 'required|integer|min:1',
            'available_seats' => 'required|integer|min:1',
            'start_date'      => 'required|date|after_or_equal:today',
            'end_date'        => 'required|date|after:start_date',
            'destination_id'  => 'required|exists:destinations,id',
            'image'           => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('trips', 'public');
        }

        Trip::create($validated);

        return redirect()->route('admin.trips.index')->with('success', 'Trip created successfully.');
    }

    public function edit(Trip $trip)
    {
        $destinations = Destination::orderBy('name')->get();
        return view('admin.trips.edit', compact('trip', 'destinations'));
    }

    public function update(Request $request, Trip $trip)
    {
        $validated = $request->validate([
            'title'           => 'required|string|max:255',
            'description'     => 'required|string',
            'price'           => 'required|numeric|min:0',
            'duration'        => 'required|integer|min:1',
            'available_seats' => 'required|integer|min:0',
            'start_date'      => 'required|date',
            'end_date'        => 'required|date|after:start_date',
            'destination_id'  => 'required|exists:destinations,id',
            'image'           => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('trips', 'public');
        }

        $trip->update($validated);

        return redirect()->route('admin.trips.index')->with('success', 'Trip updated successfully.');
    }

    public function destroy(Trip $trip)
    {
        $trip->delete();
        return back()->with('success', 'Trip deleted.');
    }
}
