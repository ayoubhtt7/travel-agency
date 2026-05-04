<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarRental;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminCarRentalController extends Controller
{
    public function index()
    {
        $cars = CarRental::with('destination')->latest()->paginate(20);
        return view('admin.cars.index', compact('cars'));
    }

    public function create()
    {
        $destinations = Destination::orderBy('name')->get();
        return view('admin.cars.create', compact('destinations'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'brand'             => 'required|string|max:100',
            'model'             => 'required|string|max:100',
            'type'              => 'required|in:economy,compact,suv,luxury,van,convertible',
            'seats'             => 'required|integer|min:1|max:20',
            'transmission'      => 'required|in:manual,automatic',
            'fuel'              => 'required|in:petrol,diesel,electric,hybrid',
            'with_ac'           => 'boolean',
            'unlimited_mileage' => 'boolean',
            'price_per_day'     => 'required|numeric|min:0',
            'available_units'   => 'required|integer|min:0',
            'destination_id'    => 'nullable|exists:destinations,id',
            'image'             => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('cars', 'public');
        }

        $data['with_ac']           = $request->boolean('with_ac');
        $data['unlimited_mileage'] = $request->boolean('unlimited_mileage');

        CarRental::create($data);

        return redirect()->route('admin.cars.index')->with('success', 'Car rental added.');
    }

    public function edit(CarRental $car)
    {
        $destinations = Destination::orderBy('name')->get();
        return view('admin.cars.edit', compact('car', 'destinations'));
    }

    public function update(Request $request, CarRental $car)
    {
        $data = $request->validate([
            'brand'             => 'required|string|max:100',
            'model'             => 'required|string|max:100',
            'type'              => 'required|in:economy,compact,suv,luxury,van,convertible',
            'seats'             => 'required|integer|min:1|max:20',
            'transmission'      => 'required|in:manual,automatic',
            'fuel'              => 'required|in:petrol,diesel,electric,hybrid',
            'with_ac'           => 'boolean',
            'unlimited_mileage' => 'boolean',
            'price_per_day'     => 'required|numeric|min:0',
            'available_units'   => 'required|integer|min:0',
            'destination_id'    => 'nullable|exists:destinations,id',
            'image'             => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($car->image) Storage::disk('public')->delete($car->image);
            $data['image'] = $request->file('image')->store('cars', 'public');
        }

        $data['with_ac']           = $request->boolean('with_ac');
        $data['unlimited_mileage'] = $request->boolean('unlimited_mileage');

        $car->update($data);

        return redirect()->route('admin.cars.index')->with('success', 'Car rental updated.');
    }

    public function destroy(CarRental $car)
    {
        if ($car->image) Storage::disk('public')->delete($car->image);
        $car->delete();
        return back()->with('success', 'Car rental deleted.');
    }
}
