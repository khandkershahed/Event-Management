<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Http\Requests\VenueStoreRequest;
use App\Http\Requests\VenueUpdateRequest;
use App\Models\Venue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VenueController extends Controller
{
    public function index(Request $request): View
    {
        $profile = $request->user()->organizerProfile;

        $venues = Venue::query()
            ->where('organizer_profile_id', $profile->id)
            ->latest()
            ->paginate(10);

        return view('organizer.venues.index', compact('venues', 'profile'));
    }

    public function create(Request $request): View
    {
        $profile = $request->user()->organizerProfile;

        return view('organizer.venues.create', compact('profile'));
    }

    public function store(VenueStoreRequest $request): RedirectResponse
    {
        $profile = $request->user()->organizerProfile;

        Venue::create(array_merge($request->validated(), [
            'organizer_profile_id' => $profile->id,
            'organizer_id' => $request->user()->id,
        ]));

        return redirect()
            ->route('organizer.venues.index')
            ->with('success', 'Venue created successfully.');
    }

    public function show(Request $request, Venue $venue): View
    {
        $this->ensureOwnVenue($request, $venue);

        return view('organizer.venues.show', compact('venue'));
    }

    public function edit(Request $request, Venue $venue): View
    {
        $this->ensureOwnVenue($request, $venue);

        return view('organizer.venues.edit', compact('venue'));
    }

    public function update(VenueUpdateRequest $request, Venue $venue): RedirectResponse
    {
        $venue->update($request->validated());

        return redirect()
            ->route('organizer.venues.index')
            ->with('success', 'Venue updated successfully.');
    }

    public function destroy(Request $request, Venue $venue): RedirectResponse
    {
        $this->ensureOwnVenue($request, $venue);

        if ($venue->events()->exists() || $venue->seatingPlans()->exists()) {
            return back()->with('error', 'This venue is already linked to events or seating plans and cannot be deleted safely.');
        }

        $venue->delete();

        return redirect()
            ->route('organizer.venues.index')
            ->with('success', 'Venue deleted successfully.');
    }

    private function ensureOwnVenue(Request $request, Venue $venue): void
    {
        $profile = $request->user()->organizerProfile;

        abort_unless($profile && (int) $venue->organizer_profile_id === (int) $profile->id, 403);
    }
}
