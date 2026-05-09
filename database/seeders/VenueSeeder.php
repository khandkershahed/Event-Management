<?php

namespace Database\Seeders;

use App\Models\OrganizerProfile;
use App\Models\Venue;
use Illuminate\Database\Seeder;

class VenueSeeder extends Seeder
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

        Venue::updateOrCreate(
            ['slug' => 'approved-events-convention-hall'],
            [
                'organizer_profile_id' => $organizer->id,
                'organizer_id' => $organizer->user_id,
                'name' => 'Approved Events Convention Hall',
                'address' => 'Agrabad Commercial Area',
                'city' => 'Chattogram',
                'country' => 'Bangladesh',
                'capacity' => 1200,
                'description' => 'Demo organizer-owned venue for testing CRUD workflows.',
            ]
        );
    }
}
