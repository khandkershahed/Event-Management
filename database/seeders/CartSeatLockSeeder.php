<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventTicket;
use App\Models\EventType;
use App\Models\OrganizerProfile;
use App\Models\SeatingPlan;
use App\Models\SeatingSeat;
use App\Models\SeatingSection;
use App\Models\Venue;
use Illuminate\Database\Seeder;

class CartSeatLockSeeder extends Seeder
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
            ['slug' => 'reservation-demo'],
            [
                'name' => 'Reservation Demo',
                'code' => 'RESDEMO',
                'serial' => 90,
                'status' => 'active',
                'added_by' => 'system',
                'updated_by' => 'system',
            ]
        );

        $venue = Venue::updateOrCreate(
            ['slug' => 'step-9-reservation-hall'],
            [
                'organizer_profile_id' => $organizer->id,
                'organizer_id' => $organizer->user_id,
                'name' => 'Step 9 Reservation Hall',
                'address' => 'Gulshan Avenue',
                'city' => 'Dhaka',
                'country' => 'Bangladesh',
                'capacity' => 300,
            ]
        );

        $plan = SeatingPlan::updateOrCreate(
            ['venue_id' => $venue->id, 'name' => 'Step 9 Seat Lock Layout'],
            [
                'organizer_profile_id' => $organizer->id,
                'status' => SeatingPlan::STATUS_ACTIVE,
                'design_json' => null,
            ]
        );

        $vipSection = SeatingSection::updateOrCreate(
            ['seating_plan_id' => $plan->id, 'name' => 'VIP Section'],
            [
                'type' => 'seat',
                'capacity' => 10,
                'x' => 0,
                'y' => 0,
                'rotation' => 0,
            ]
        );

        $generalSection = SeatingSection::updateOrCreate(
            ['seating_plan_id' => $plan->id, 'name' => 'General Section'],
            [
                'type' => 'seat',
                'capacity' => 20,
                'x' => 0,
                'y' => 120,
                'rotation' => 0,
            ]
        );

        foreach (range(1, 5) as $number) {
            SeatingSeat::updateOrCreate(
                ['section_id' => $vipSection->id, 'label' => 'VIP-' . $number],
                ['row_label' => 'VIP', 'seat_number' => $number, 'x' => $number * 45, 'y' => 10]
            );

            SeatingSeat::updateOrCreate(
                ['section_id' => $generalSection->id, 'label' => 'GA-' . $number],
                ['row_label' => 'GA', 'seat_number' => $number, 'x' => $number * 45, 'y' => 120]
            );
        }

        $event = Event::updateOrCreate(
            ['slug' => 'step-9-cart-seat-lock-demo'],
            [
                'organizer_profile_id' => $organizer->id,
                'event_type_id' => $type->id,
                'venue_id' => $venue->id,
                'seating_plan_id' => $plan->id,
                'name' => 'Step 9 Cart and Seat Lock Demo',
                'tagline' => 'Demo event for cart reservations and seat locks.',
                'description' => 'This published event contains both general admission and reserved-seat tickets for Step 9 testing.',
                'start_date' => now()->addWeeks(3)->toDateString(),
                'end_date' => now()->addWeeks(3)->toDateString(),
                'start_time' => '18:00:00',
                'end_time' => '21:00:00',
                'venue' => $venue->name,
                'organizer_name' => $organizer->organization_name,
                'organizer_brand' => $organizer->organization_name,
                'total_capacity' => 300,
                'status' => Event::STATUS_PUBLISHED,
                'approved_at' => now(),
                'is_featured' => true,
                'added_by' => 'system',
                'updated_by' => 'system',
            ]
        );

        EventTicket::updateOrCreate(
            ['event_id' => $event->id, 'name' => 'General Admission'],
            [
                'description' => 'Standard quantity-based ticket for cart testing.',
                'ticket_type' => EventTicket::TYPE_PAID,
                'price' => 500,
                'currency' => 'BDT',
                'quantity' => 100,
                'sold_quantity' => 0,
                'min_per_order' => 1,
                'max_per_order' => 4,
                'sales_start_at' => now()->subDay(),
                'sales_end_at' => now()->addWeeks(2),
                'visibility' => EventTicket::VISIBILITY_PUBLIC,
                'status' => EventTicket::STATUS_ACTIVE,
                'valid_section_ids' => null,
                'platform_fee_type' => 'none',
                'platform_fee_value' => 0,
                'organizer_absorbs_fee' => false,
                'is_active' => true,
            ]
        );

        EventTicket::updateOrCreate(
            ['event_id' => $event->id, 'name' => 'VIP Reserved Seat'],
            [
                'description' => 'Reserved-seat ticket restricted to the VIP Section.',
                'ticket_type' => EventTicket::TYPE_PAID,
                'price' => 1500,
                'currency' => 'BDT',
                'quantity' => 10,
                'sold_quantity' => 0,
                'min_per_order' => 1,
                'max_per_order' => 2,
                'sales_start_at' => now()->subDay(),
                'sales_end_at' => now()->addWeeks(2),
                'visibility' => EventTicket::VISIBILITY_PUBLIC,
                'status' => EventTicket::STATUS_ACTIVE,
                'valid_section_ids' => [$vipSection->id],
                'platform_fee_type' => 'none',
                'platform_fee_value' => 0,
                'organizer_absorbs_fee' => false,
                'is_active' => true,
            ]
        );
    }
}
