<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\SeatingSection;
use App\Services\SeatLockService;
use App\Services\Seating\PublicSeatMapService;
use Illuminate\Http\Request;

class SeatMapController extends Controller
{
    public function __construct(
        protected SeatLockService $seatLockService,
        protected PublicSeatMapService $publicSeatMapService
    ) {
    }

    /**
     * Return a clean structured JSON for the public visual selector.
     */
    public function getStructuredMap(Event $event, Request $request)
    {
        abort_unless($event->status === Event::STATUS_PUBLISHED, 404);

        $event->loadMissing(['seatingPlan.sections.seats', 'publicTickets']);

        if (! $event->seatingPlan) {
            return response()->json([
                'status' => 'error',
                'message' => 'No seating plan assigned to this event.',
            ], 404);
        }

        $seatStatuses = $this->seatLockService->seatStatusMap($event, $request->session()->getId(), auth()->id());
        $seatMap = $this->publicSeatMapService->build($event, $seatStatuses);

        return response()->json([
            'status' => 'success',
            'map' => [
                'event_id' => $event->id,
                'plan_id' => $event->seatingPlan->id,
                'plan_name' => $event->seatingPlan->name,
                'has_visual_map' => $seatMap['has_visual_map'],
                'blocks' => $seatMap['blocks'],
                'fallback_sections' => $seatMap['fallback_sections'],
                'selected_seats' => $seatMap['selected_seats'],
            ],
        ]);
    }

    /**
     * Return only seats for one section, including public status metadata.
     */
    public function getSectionSeats(Event $event, SeatingSection $section, Request $request)
    {
        abort_unless($event->status === Event::STATUS_PUBLISHED, 404);

        if ((int) $section->seating_plan_id !== (int) $event->seating_plan_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Section does not belong to this event.',
            ], 422);
        }

        $event->loadMissing(['seatingPlan.sections.seats', 'publicTickets']);
        $seatStatuses = $this->seatLockService->seatStatusMap($event, $request->session()->getId(), auth()->id());
        $seatMap = $this->publicSeatMapService->build($event, $seatStatuses);

        $matchingSection = collect($seatMap['fallback_sections'])
            ->firstWhere('id', $section->id);

        if (! $matchingSection) {
            foreach ($seatMap['blocks'] as $block) {
                if ((int) ($block['section_id'] ?? 0) === (int) $section->id) {
                    $matchingSection = [
                        'id' => $section->id,
                        'name' => $section->name,
                        'type' => $section->type,
                        'seats' => $block['seats'] ?? [],
                    ];
                    break;
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'seats' => $matchingSection['seats'] ?? [],
        ]);
    }
}
