<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventType;
use App\Models\OrganizerProfile;
use App\Models\SeatingPlan;
use App\Models\Venue;
use Illuminate\Database\Seeder;

class EventMarketplaceSeeder extends Seeder
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

        $type = EventType::updateOrCreate(
            ['slug' => 'conference'],
            [
                'name' => 'Conference',
                'code' => 'CONF',
                'serial' => 1,
                'status' => 'active',
                'added_by' => 'system',
                'updated_by' => 'system',
            ]
        );

        $venue = Venue::firstOrCreate(
            ['slug' => 'approved-events-convention-hall'],
            [
                'organizer_profile_id' => $organizer->id,
                'organizer_id' => $organizer->user_id,
                'name' => 'Approved Events Convention Hall',
                'address' => 'Agrabad Commercial Area',
                'city' => 'Chattogram',
                'country' => 'Bangladesh',
                'capacity' => 1200,
            ]
        );

        $plan = SeatingPlan::firstOrCreate(
            ['venue_id' => $venue->id, 'name' => 'Main Hall Layout'],
            [
                'organizer_profile_id' => $organizer->id,
                'status' => SeatingPlan::STATUS_ACTIVE,
                'design_json' => null,
            ]
        );

        Event::updateOrCreate(
            ['slug' => 'approved-organizer-tech-conference'],
            [
                'organizer_profile_id' => $organizer->id,
                'event_type_id' => $type->id,
                'venue_id' => $venue->id,
                'seating_plan_id' => $plan->id,
                'name' => 'Approved Organizer Tech Conference',
                'tagline' => 'A demo event for marketplace testing.',
                'description' => 'This is a seeded organizer-owned published event.',
                'start_date' => now()->addMonth()->toDateString(),
                'end_date' => now()->addMonth()->toDateString(),
                'start_time' => '10:00:00',
                'end_time' => '16:00:00',
                'venue' => $venue->name,
                'organizer_name' => $organizer->organization_name,
                'organizer_brand' => $organizer->organization_name,
                'total_capacity' => 500,
                'status' => Event::STATUS_PUBLISHED,
                'approved_at' => now(),
                'is_featured' => true,
                'added_by' => 'system',
                'updated_by' => 'system',
            ]
        );
    }
}
