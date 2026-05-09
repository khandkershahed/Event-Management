<?php

namespace Database\Seeders;

use App\Models\OrganizerProfile;
use App\Models\SeatingPlan;
use App\Models\Venue;
use Illuminate\Database\Seeder;

class SeatingPlanSeeder extends Seeder
{
    public function run(): void
    {
        $organizer = OrganizerProfile::where('status', OrganizerProfile::STATUS_APPROVED)->first();

        if (! $organizer) {
            $this->call(OrganizerSeeder::class);
            $organizer = OrganizerProfile::where('status', OrganizerProfile::STATUS_APPROVED)->first();
        }

        if (! $organizer) {
            return;
        }

        $venue = Venue::where('organizer_profile_id', $organizer->id)->first();

        if (! $venue) {
            $this->call(VenueSeeder::class);
            $venue = Venue::where('organizer_profile_id', $organizer->id)->first();
        }

        if (! $venue) {
            return;
        }

        SeatingPlan::updateOrCreate(
            [
                'organizer_profile_id' => $organizer->id,
                'venue_id' => $venue->id,
                'name' => 'Main Hall Theater Layout',
            ],
            [
                'status' => SeatingPlan::STATUS_DRAFT,
                'design_json' => [
                    ['type' => 'section', 'name' => 'VIP', 'capacity' => 50, 'x' => 120, 'y' => 80],
                    ['type' => 'section', 'name' => 'General', 'capacity' => 250, 'x' => 120, 'y' => 220],
                ],
            ]
        );
    }
}
