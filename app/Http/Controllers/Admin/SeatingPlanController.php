<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use App\Models\SeatingPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SeatingPlanController extends Controller
{
    /**
     * Display all seating plans.
     */
    public function index()
    {
        return view('admin.pages.seating_plans.index', [
            'plans' => SeatingPlan::with('venue')->latest()->get(),
        ]);
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view('admin.pages.seating_plans.create', [
            'venues' => Venue::orderBy('name')->get(),
        ]);
    }

    /**
     * Store seating plan (JSON empty until designer edits).
     */
    public function store(Request $request)
    {
        $request->validate([
            'venue_id' => 'required|exists:venues,id',
            'name'     => 'required|string|max:255',
        ]);

        $plan = SeatingPlan::create([
            'venue_id' => $request->venue_id,
            'name'     => $request->name,
            'design_json' => null, // Designer will update this
        ]);

        return redirect()
            ->route('admin.seating-plans.designer', $plan->id)
            ->with('success', 'Seating plan created. Now design the layout.');
    }

    /**
     * Edit seating plan metadata (not design).
     */
    public function edit(SeatingPlan $seating_plan)
    {
        return view('admin.pages.seating_plans.edit', [
            'plan'   => $seating_plan,
            'venues' => Venue::orderBy('name')->get(),
        ]);
    }

    /**
     * Update seating plan metadata.
     */
    public function update(Request $request, SeatingPlan $seating_plan)
    {
        $request->validate([
            'venue_id' => 'required|exists:venues,id',
            'name'     => 'required|string|max:255',
        ]);

        $seating_plan->update([
            'venue_id' => $request->venue_id,
            'name'     => $request->name,
        ]);

        return redirect()
            ->route('admin.seating-plans.index')
            ->with('success', 'Seating plan updated successfully.');
    }

    /**
     * Delete seating plan.
     */
    public function destroy(SeatingPlan $seating_plan)
    {
        $seating_plan->delete();
    }
}
