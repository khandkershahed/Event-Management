<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use App\Models\SeatingPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VenueController extends Controller
{
    /**
     * Display a listing of venues.
     */
    public function index()
    {
        return view('admin.pages.venue.index', [
            'venues' => Venue::latest()->get(),
        ]);
    }

    /**
     * Show the form for creating a new venue.
     */
    public function create()
    {
        return view('admin.pages.venue.create');
    }

    /**
     * Store a newly created venue in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'address' => 'nullable|string',
            'city'    => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer',
        ]);

        Venue::create([
            'name'        => $request->name,
            'address'     => $request->address,
            'city'        => $request->city,
            'country'     => $request->country,
            'capacity'    => $request->capacity,
            'organizer_id' => auth('admin')->id(),
            'description'  => $request->description,
            'image'        => $request->image ?? null,
        ]);

        return redirect()->route('admin.venue.index')
            ->with('success', 'Venue created successfully.');
    }

    /**
     * Show the form for editing a venue.
     */
    public function edit(Venue $venue)
    {
        return view('admin.pages.venue.edit', [
            'venue' => $venue,
            'seatingPlans' => SeatingPlan::where('venue_id', $venue->id)->get(),
        ]);
    }

    /**
     * Update the venue.
     */
    public function update(Request $request, Venue $venue)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'address' => 'nullable|string',
            'city'    => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer',
        ]);

        $venue->update([
            'name'        => $request->name,
            'address'     => $request->address,
            'city'        => $request->city,
            'country'     => $request->country,
            'capacity'    => $request->capacity,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.venue.index')
            ->with('success', 'Venue updated successfully.');
    }

    /**
     * Remove the venue.
     */
    public function destroy(Venue $venue)
    {
        $venue->delete();
    }
}
