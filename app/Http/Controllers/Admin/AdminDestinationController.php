<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\Request;

class AdminDestinationController extends Controller
{
    public function index()
    {
        $destinations = Destination::withCount('trips')->latest()->get();
        return view('admin.destinations.index', compact('destinations'));
    }

    public function create()
    {
        return view('admin.destinations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'country'     => 'required|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('destinations', 'public');
        }

        Destination::create($validated);

        return redirect()->route('admin.destinations.index')->with('success', 'Destination created.');
    }

    public function edit(Destination $destination)
    {
        return view('admin.destinations.edit', compact('destination'));
    }

    public function update(Request $request, Destination $destination)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'country'     => 'required|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('destinations', 'public');
        }

        $destination->update($validated);

        return redirect()->route('admin.destinations.index')->with('success', 'Destination updated.');
    }

    public function destroy(Destination $destination)
    {
        $destination->delete();
        return back()->with('success', 'Destination deleted.');
    }
}
