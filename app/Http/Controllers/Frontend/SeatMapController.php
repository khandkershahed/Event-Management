<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\SeatingPlan;
use App\Models\SeatingSection;
use App\Models\SeatingSeat;
use Illuminate\Http\Request;

class SeatMapController extends Controller
{
    /**
     * Return a clean structured JSON for the seat selector
     * Instead of sending raw designer JSON.
     */
    public function getStructuredMap(Event $event)
    {
        $plan = $event->seatingPlan;

        if (!$plan) {
            return response()->json([
                'status'  => 'error',
                'message' => 'No seating plan assigned to this event.',
            ], 404);
        }

        // Fetch all sections + seats
        $sections = SeatingSection::where('seating_plan_id', $plan->id)
            ->with('seats')
            ->orderBy('name')
            ->get();

        // Normalize for frontend
        $data = [
            'event_id'     => $event->id,
            'plan_id'      => $plan->id,
            'plan_name'    => $plan->name,
            'sections'     => [],
        ];

        foreach ($sections as $section) {
            $data['sections'][] = [
                'id'        => $section->id,
                'name'      => $section->name,
                'type'      => $section->type,
                'capacity'  => $section->capacity,
                'x'         => $section->x,
                'y'         => $section->y,
                'rotation'  => $section->rotation,
                'seats'     => $section->seats->map(function ($seat) {
                    return [
                        'id'          => $seat->id,
                        'label'       => $seat->label,
                        'row_label'   => $seat->row_label,
                        'seat_number' => $seat->seat_number,
                        'x'           => $seat->x,
                        'y'           => $seat->y,
                    ];
                })->toArray(),
            ];
        }

        return response()->json([
            'status' => 'success',
            'map'    => $data,
        ]);
    }


    /**
     * Return only seats (for realtime status refresh)
     */
    public function getSectionSeats(Event $event, SeatingSection $section)
    {
        if ($section->seating_plan_id !== $event->seating_plan_id) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Section does not belong to this event.',
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'seats'  => $section->seats->map(function ($seat) {
                return [
                    'id'          => $seat->id,
                    'label'       => $seat->label,
                    'row_label'   => $seat->row_label,
                    'seat_number' => $seat->seat_number,
                    'x'           => $seat->x,
                    'y'           => $seat->y,
                ];
            }),
        ]);
    }
}
