<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    public function run()
    {
        $eventTypes = DB::table('event_types')->get();
        $now = Carbon::now();
        $events = [];

        // A list of real-style events per type
        $realEventData = [
            'Wedding' => [
                'The Royal Bengali Wedding',
                'Boho Beach Destination Wedding',
                'Classic Church Ceremony',
                'Luxury Dhaka Wedding Showcase',
            ],
            'Corporate Meeting' => [
                'Annual Strategy Meeting 2025',
                'CXO Breakfast Networking',
                'Q3 Sales Planning Retreat',
                'IT Leadership Roundtable',
            ],
            'Birthday Party' => [
                'Princess Themed Birthday Bash',
                'Superhero Kids Party',
                'Golden Jubilee Celebration',
                'Outdoor Adventure Birthday',
            ],
            'Conference' => [
                'TechWorld Conference 2025',
                'Healthcare Innovations Summit',
                'Bangladesh Startup Conference',
                'Global Marketing Leaders Forum',
            ],
            'Concert' => [
                'Summer Rock Fest',
                'Jazz Night in Dhaka',
                'EDM Party Bash',
                'Folk Music Fiesta',
            ],
            'Product Launch' => [
                'iNova Smartwatch Launch',
                'EcoCar 2.0 Reveal',
                'BeautyBliss Product Demo Day',
                'FutureGadget Unboxing Event',
            ],
            'Seminar' => [
                'AI & Robotics: The Future',
                'Nutrition & Wellness Seminar',
                'Digital Privacy Awareness',
                'Leadership Growth Seminar',
            ],
            'Exhibition' => [
                'Dhaka Art Expo',
                'TechGear Expo 2025',
                'Home & Living Fair',
                'Photography World Exhibit',
            ],
            'Workshop' => [
                'Photography Masterclass',
                'Creative Writing Bootcamp',
                'Advanced UI/UX Workshop',
                'Culinary Skills Training',
            ],
            'Award Ceremony' => [
                'Entrepreneur Awards Night',
                'Youth Talent Recognition Gala',
                'Best Teacher Awards 2025',
                'Music Icon Award Ceremony',
            ]
        ];

        foreach ($eventTypes as $type) {
            $eventsForType = $realEventData[$type->name] ?? [];

            foreach ($eventsForType as $i => $eventName) {
                $slug = Str::slug($eventName . '-' . Str::random(4));
                $startDate = Carbon::now()->addDays(rand(15, 120));
                $endDate = (clone $startDate)->addDays(rand(0, 2));
                $startTime = Carbon::createFromTime(rand(10, 16), 0, 0);
                $endTime = (clone $startTime)->addHours(rand(2, 5));

                $events[] = [
                    'event_type_id'         => $type->id,
                    'name'                  => $eventName,
                    'slug'                  => $slug,
                    'tagline'               => "Don't miss out on the {$eventName}",
                    'description'           => "Join us at the {$eventName} for an unforgettable experience, filled with excitement, insights, and inspiration.",
                    'logo'                  => 'logos/' . $slug . '.png',
                    'image'                 => 'images/' . $slug . '.jpg',
                    'banner_image'          => 'banners/' . $slug . '.jpg',
                    'video_teaser_url'      => 'https://www.youtube.com/watch?v=' . Str::random(8),
                    'location_map_url'      => 'https://maps.google.com/?q=Dhaka',
                    'start_date'            => $startDate->toDateString(),
                    'end_date'              => $endDate->toDateString(),
                    'start_time'            => $startTime->toTimeString(),
                    'end_time'              => $endTime->toTimeString(),
                    'venue'                 => 'Bangabandhu International Conference Center, Dhaka',
                    'organizer_name'        => 'EventPro Ltd.',
                    'organizer_brand'       => 'EventPro',
                    'purchase_deadline'     => $startDate->subDays(2),
                    'total_capacity'        => rand(200, 2000),
                    'age_restriction'       => rand(0, 1) ? '18+' : 'All Ages',
                    'event_type'            => $type->name,
                    'terms_and_conditions'  => 'Tickets are non-refundable. Entry requires valid ID.',
                    'added_by'              => 'admin',
                    'updated_by'            => 'admin',
                    'status'                => 'active',
                    'created_at'            => now(),
                    'updated_at'            => now(),
                ];
            }
        }

        DB::table('events')->insert($events);
    }
}
