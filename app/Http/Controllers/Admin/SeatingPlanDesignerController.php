<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeatingPlan;
use App\Models\SeatingSection;
use App\Models\SeatingSeat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SeatingPlanDesignerController extends Controller
{
    /**
     * Show the seat map designer interface.
     */
    public function designer($id)
    {
        $plan = SeatingPlan::with('venue')->findOrFail($id);

        return view('admin.pages.seating_plans.designer', [
            'plan'       => $plan,
            'venue'      => $plan->venue,
            'designJson' => $plan->design_json ? json_encode($plan->design_json) : '[]',
        ]);
    }

    /**
     * Save designer JSON + generate seating_sections + seating_seats.
     */
    public function save(Request $request, $id)
    {
        $plan = SeatingPlan::findOrFail($id);

        $request->validate([
            'design_json' => 'required',
            'sections'    => 'present|array',
        ]);

        try {
            DB::transaction(function () use ($request, $plan) {

                /**
                 * 1. SAVE THE RAW VISUAL JSON
                 */
                $designData = is_string($request->design_json)
                    ? json_decode($request->design_json, true)
                    : $request->design_json;

                if ($designData === null && json_last_error() !== JSON_ERROR_NONE) {
                    throw new \Exception("Invalid seatmap JSON.");
                }

                $plan->update([
                    'design_json' => $designData,
                ]);


                /**
                 * 2. DELETE OLD SECTION + SEAT INVENTORY
                 * Cascades will automatically delete old seats.
                 */
                $plan->sections()->delete();


                /**
                 * 3. REBUILD INVENTORY (the "explosion" step)
                 */
                foreach ($request->sections as $sec) {

                    // Create the section
                    $section = SeatingSection::create([
                        'seating_plan_id' => $plan->id,
                        'name'            => $sec['name'],
                        'type'            => $sec['type'] ?? 'seat',
                        'capacity'        => $sec['capacity'] ?? 0,

                        // Visual placement meta
                        'x'               => $sec['x'] ?? 0,
                        'y'               => $sec['y'] ?? 0,
                        'rotation'        => $sec['rotation'] ?? 0,
                    ]);

                    // Prepare seats for bulk insert
                    if (!empty($sec['seats']) && is_array($sec['seats'])) {
                        $bulk = [];

                        foreach ($sec['seats'] as $seat) {
                            $bulk[] = [
                                'section_id'  => $section->id,
                                'label'       => $seat['label'] ?? 'Seat',
                                'row_label'   => $seat['row_label'] ?? null,
                                'seat_number' => $seat['seat_number'] ?? null,
                                'x'           => $seat['x'],
                                'y'           => $seat['y'],
                                'created_at'  => now(),
                                'updated_at'  => now(),
                            ];
                        }

                        if (!empty($bulk)) {
                            SeatingSeat::insert($bulk);
                        }
                    }
                }
            });

            if ($request->wantsJson()) {
                return response()->json([
                    'status'  => 'success',
                    'message' => 'Seat map saved and database inventory updated.',
                ]);
            }

            return redirect()
                ->route('admin.seating-plans.designer', $plan->id)
                ->with('success', 'Seat map saved successfully!');

        } catch (\Exception $e) {
            Log::error('Seatmap Save Error: ' . $e->getMessage());

            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Save failed: ' . $e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Error saving map: ' . $e->getMessage());
        }
    }
}
