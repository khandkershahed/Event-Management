<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventTicket;
use App\Models\SeatingSection;
use Illuminate\Database\Seeder;

class EventTicketSeeder extends Seeder
{
    public function run(): void
    {
        $event = Event::where('status', Event::STATUS_PUBLISHED)->first();

        if (! $event) {
            $this->call(EventMarketplaceSeeder::class);
            $event = Event::where('status', Event::STATUS_PUBLISHED)->first();
        }

        if (! $event) {
            return;
        }

        $sections = collect();
        if ($event->seating_plan_id) {
            $sections = SeatingSection::where('seating_plan_id', $event->seating_plan_id)->pluck('id');
        }

        $common = [
            'event_id' => $event->id,
            'currency' => 'BDT',
            'sold_quantity' => 0,
            'min_per_order' => 1,
            'visibility' => EventTicket::VISIBILITY_PUBLIC,
            'status' => EventTicket::STATUS_ACTIVE,
            'is_active' => true,
            'sales_start_at' => now()->subDay(),
            'sales_end_at' => now()->addWeeks(3),
            'platform_fee_type' => EventTicket::FEE_PERCENT,
            'platform_fee_value' => 5,
            'organizer_absorbs_fee' => false,
            'valid_section_ids' => $sections->take(2)->values()->all(),
        ];

        EventTicket::updateOrCreate(
            ['event_id' => $event->id, 'name' => 'Free Community Pass'],
            array_merge($common, [
                'ticket_type' => EventTicket::TYPE_FREE,
                'description' => 'Limited free access for community guests.',
                'price' => 0,
                'quantity' => 50,
                'max_per_order' => 2,
                'platform_fee_type' => EventTicket::FEE_NONE,
                'platform_fee_value' => 0,
            ])
        );

        EventTicket::updateOrCreate(
            ['event_id' => $event->id, 'name' => 'General Admission'],
            array_merge($common, [
                'ticket_type' => EventTicket::TYPE_PAID,
                'description' => 'Standard entry ticket for the event.',
                'price' => 750,
                'quantity' => 300,
                'max_per_order' => 6,
            ])
        );

        EventTicket::updateOrCreate(
            ['event_id' => $event->id, 'name' => 'VIP Experience'],
            array_merge($common, [
                'ticket_type' => EventTicket::TYPE_PAID,
                'description' => 'Premium access with reserved seating and extras.',
                'price' => 2500,
                'quantity' => 80,
                'max_per_order' => 4,
                'valid_section_ids' => $sections->take(1)->values()->all(),
            ])
        );

        EventTicket::updateOrCreate(
            ['event_id' => $event->id, 'name' => 'Sponsor Invitation'],
            array_merge($common, [
                'ticket_type' => EventTicket::TYPE_INVITE_ONLY,
                'description' => 'Hidden invite-only ticket for sponsors and partners.',
                'price' => 0,
                'quantity' => 25,
                'max_per_order' => 1,
                'visibility' => EventTicket::VISIBILITY_HIDDEN,
                'platform_fee_type' => EventTicket::FEE_NONE,
                'platform_fee_value' => 0,
            ])
        );
    }
}
