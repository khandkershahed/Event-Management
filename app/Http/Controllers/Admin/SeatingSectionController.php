<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeatingPlan;
use App\Models\SeatingSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SeatingSectionController extends Controller
{
    /**
     * Return all sections for a seating plan
     */
    public function index($planId)
    {
        $plan = SeatingPlan::with('sections')->findOrFail($planId);

        return response()->json([
            'sections' => $plan->sections
        ]);
    }

    /**
     * Store a new section (AJAX)
     */
    public function store(Request $request, $planId)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'type'      => 'required|string|max:50',
            'capacity'  => 'nullable|integer|min:0',
            'x'         => 'nullable|integer',
            'y'         => 'nullable|integer',
            'rotation'  => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'   => 'error',
                'errors'   => $validator->errors(),
            ], 422);
        }

        $plan = SeatingPlan::findOrFail($planId);

        $section = SeatingSection::create([
            'seating_plan_id' => $plan->id,
            'name'            => $request->name,
            'type'            => $request->type,
            'capacity'        => $request->capacity ?? 0,
            'x'               => $request->x ?? 0,
            'y'               => $request->y ?? 0,
            'rotation'        => $request->rotation ?? 0,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Section created successfully.',
            'section' => $section,
        ]);
    }

    /**
     * Show a single section for edit (AJAX)
     */
    public function show($id)
    {
        $section = SeatingSection::findOrFail($id);

        return response()->json([
            'section' => $section
        ]);
    }

    /**
     * Update a section (AJAX)
     */
    public function update(Request $request, $id)
    {
        $section = SeatingSection::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'type'      => 'required|string|max:50',
            'capacity'  => 'nullable|integer|min:0',
            'x'         => 'nullable|integer',
            'y'         => 'nullable|integer',
            'rotation'  => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'   => 'error',
                'errors'   => $validator->errors(),
            ], 422);
        }

        $section->update([
            'name'       => $request->name,
            'type'       => $request->type,
            'capacity'   => $request->capacity ?? $section->capacity,
            'x'          => $request->x ?? $section->x,
            'y'          => $request->y ?? $section->y,
            'rotation'   => $request->rotation ?? $section->rotation,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Section updated successfully.',
            'section' => $section,
        ]);
    }

    /**
     * Delete a section
     */
    public function destroy($id)
    {
        $section = SeatingSection::findOrFail($id);
        $section->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Section deleted successfully.'
        ]);
    }

    /**
     * For TicketTypeController — returns only id + name for checkboxes
     */
    public function simple($planId)
    {
        $sections = SeatingSection::where('seating_plan_id', $planId)
            ->select('id', 'name')
            ->get();

        return response()->json($sections);
    }
}
