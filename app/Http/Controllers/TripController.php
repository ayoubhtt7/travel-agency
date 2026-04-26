<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\Request;

class TripController extends Controller
{
    public function index(Request $request)
    {
        $trips = Trip::with('destination')
            ->when($request->search, fn($q) => $q->where('title', 'like', '%' . $request->search . '%'))
            ->latest()
            ->paginate(12);

        return view('trips.index', compact('trips'));
    }

    public function show($id)
    {
        $trip = Trip::with(['destination', 'reviews.user'])->findOrFail($id);
        return view('trips.show', compact('trip'));
    }
}
