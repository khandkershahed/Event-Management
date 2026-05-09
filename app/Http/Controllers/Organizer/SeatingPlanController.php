<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Http\Requests\SeatingPlanStoreRequest;
use App\Http\Requests\SeatingPlanUpdateRequest;
use App\Models\SeatingPlan;
use App\Models\Venue;
use App\Services\SeatingPlanCloneService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SeatingPlanController extends Controller
{
    public function index(Request $request): View
    {
        $profile = $request->user()->organizerProfile;

        $plans = SeatingPlan::with('venue')
            ->where('organizer_profile_id', $profile->id)
            ->latest()
            ->paginate(10);

        return view('organizer.seating-plans.index', compact('plans', 'profile'));
    }

    public function create(Request $request): View|RedirectResponse
    {
        $profile = $request->user()->organizerProfile;
        $venues = $profile->venues()->orderBy('name')->get();

        if ($venues->isEmpty()) {
            return redirect()->route('organizer.venues.create')->with('error', 'Please create a venue before creating a seating plan.');
        }

        $plan = new SeatingPlan(['status' => SeatingPlan::STATUS_DRAFT]);

        return view('organizer.seating-plans.create', compact('plan', 'venues'));
    }

    public function store(SeatingPlanStoreRequest $request): RedirectResponse
    {
        $profile = $request->user()->organizerProfile;

        $venue = Venue::where('organizer_profile_id', $profile->id)->findOrFail($request->integer('venue_id'));

        $plan = SeatingPlan::create([
            'organizer_profile_id' => $profile->id,
            'venue_id' => $venue->id,
            'name' => $request->string('name')->toString(),
            'status' => $request->input('status', SeatingPlan::STATUS_DRAFT),
            'design_json' => null,
        ]);

        return redirect()->route('organizer.seating-plans.show', $plan)->with('success', 'Seating plan created successfully.');
    }

    public function show(Request $request, SeatingPlan $seating_plan): View
    {
        $this->ensureOwnPlan($request, $seating_plan);
        $seating_plan->load(['venue', 'sections.seats']);

        return view('organizer.seating-plans.show', ['plan' => $seating_plan]);
    }

    public function edit(Request $request, SeatingPlan $seating_plan): View
    {
        $this->ensureOwnPlan($request, $seating_plan);
        abort_if($seating_plan->isLocked(), 403, 'Locked seating plans cannot be edited.');

        $venues = $request->user()->organizerProfile->venues()->orderBy('name')->get();

        return view('organizer.seating-plans.edit', ['plan' => $seating_plan, 'venues' => $venues]);
    }

    public function update(SeatingPlanUpdateRequest $request, SeatingPlan $seating_plan): RedirectResponse
    {
        $seating_plan->update([
            'venue_id' => $request->integer('venue_id'),
            'name' => $request->string('name')->toString(),
            'status' => $request->input('status', $seating_plan->status),
        ]);

        return redirect()->route('organizer.seating-plans.index')->with('success', 'Seating plan updated successfully.');
    }

    public function destroy(Request $request, SeatingPlan $seating_plan): RedirectResponse
    {
        $this->ensureOwnPlan($request, $seating_plan);

        if ($seating_plan->isLocked() || $seating_plan->events()->exists()) {
            return back()->with('error', 'This seating plan is locked or linked to events and cannot be deleted safely.');
        }

        $seating_plan->delete();

        return redirect()->route('organizer.seating-plans.index')->with('success', 'Seating plan deleted successfully.');
    }

    public function duplicate(Request $request, SeatingPlan $seating_plan, SeatingPlanCloneService $cloneService): RedirectResponse
    {
        $this->ensureOwnPlan($request, $seating_plan);

        $request->validate(['name' => ['nullable', 'string', 'max:255']]);

        $clone = $cloneService->duplicate($seating_plan, $request->input('name') ?: $seating_plan->name . ' Copy');

        return redirect()->route('organizer.seating-plans.edit', $clone)->with('success', 'Seating plan duplicated successfully.');
    }

    private function ensureOwnPlan(Request $request, SeatingPlan $plan): void
    {
        $profile = $request->user()->organizerProfile;

        abort_unless($profile && (int) $plan->organizer_profile_id === (int) $profile->id, 403);
    }
}
