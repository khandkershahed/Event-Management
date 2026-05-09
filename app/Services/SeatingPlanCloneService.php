<?php

namespace App\Services;

use App\Models\SeatingPlan;
use Illuminate\Support\Facades\DB;

class SeatingPlanCloneService
{
    public function duplicate(SeatingPlan $source, string $name): SeatingPlan
    {
        return DB::transaction(function () use ($source, $name): SeatingPlan {
            $clone = SeatingPlan::create([
                'organizer_profile_id' => $source->organizer_profile_id,
                'venue_id' => $source->venue_id,
                'name' => $name,
                'status' => SeatingPlan::STATUS_DRAFT,
                'design_json' => $source->design_json,
            ]);

            $source->loadMissing('sections.seats');

            foreach ($source->sections as $section) {
                $newSection = $clone->sections()->create([
                    'name' => $section->name,
                    'type' => $section->type,
                    'capacity' => $section->capacity,
                    'x' => $section->x,
                    'y' => $section->y,
                    'rotation' => $section->rotation,
                ]);

                foreach ($section->seats as $seat) {
                    $newSection->seats()->create([
                        'label' => $seat->label,
                        'row_label' => $seat->row_label,
                        'seat_number' => $seat->seat_number,
                        'x' => $seat->x,
                        'y' => $seat->y,
                    ]);
                }
            }

            return $clone;
        });
    }
}
