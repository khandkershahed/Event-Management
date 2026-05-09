<?php

namespace Database\Seeders;

use App\Models\CartItem;
use App\Models\Event;
use App\Models\EventTicket;
use App\Models\EventType;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderTicket;
use App\Models\OrganizerProfile;
use App\Models\SeatLock;
use App\Models\SeatingPlan;
use App\Models\SeatingSeat;
use App\Models\SeatingSection;
use App\Models\User;
use App\Models\Venue;
use App\Services\Seating\SeatingPlanDesignService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdvancedPublicSeatSelectionSeeder extends Seeder
{
    public function run(): void
    {
        $organizerUser = User::query()->updateOrCreate(
            ['email' => 'advanced.public.organizer@example.com'],
            [
                'name' => 'Advanced Public Organizer',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $customer = User::query()->updateOrCreate(
            ['email' => 'advanced.public.customer@example.com'],
            [
                'name' => 'Advanced Public Customer',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $otherCustomer = User::query()->updateOrCreate(
            ['email' => 'advanced.public.other@example.com'],
            [
                'name' => 'Advanced Other Customer',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $organizer = OrganizerProfile::query()->updateOrCreate(
            ['slug' => 'advanced-public-seat-selection-organizer'],
            [
                'user_id' => $organizerUser->id,
                'organization_name' => 'Advanced Public Seat Selection Organizer',
                'email' => $organizerUser->email,
                'status' => OrganizerProfile::STATUS_APPROVED,
                'approved_at' => now(),
            ]
        );

        $venue = Venue::query()->updateOrCreate(
            ['slug' => 'advanced-public-seat-selection-arena'],
            [
                'organizer_profile_id' => $organizer->id,
                'organizer_id' => $organizerUser->id,
                'name' => 'Advanced Public Seat Selection Arena',
                'address' => 'Public Visual Demo Road',
                'city' => 'Dhaka',
                'country' => 'Bangladesh',
                'capacity' => 600,
            ]
        );

        $plan = SeatingPlan::query()->updateOrCreate(
            ['venue_id' => $venue->id, 'name' => 'Advanced Public Visual Buyer Layout'],
            [
                'organizer_profile_id' => $organizer->id,
                'status' => SeatingPlan::STATUS_ACTIVE,
            ]
        );

        $eventType = EventType::query()->firstOrCreate(
            ['slug' => 'advanced-public-seat-selection'],
            ['name' => 'Advanced Public Seat Selection', 'status' => 'active']
        );

        $event = Event::query()->updateOrCreate(
            ['slug' => 'advanced-public-seat-selection-demo'],
            [
                'organizer_profile_id' => $organizer->id,
                'event_type_id' => $eventType->id,
                'venue_id' => $venue->id,
                'seating_plan_id' => $plan->id,
                'name' => 'Advanced Public Seat Selection Demo',
                'tagline' => 'A seeded buyer-facing visual seat map with available, selected, locked, sold, and disabled seats.',
                'description' => '<p>This event validates Advanced Step A3 public visual seat selection.</p>',
                'start_date' => now()->addWeeks(8)->toDateString(),
                'end_date' => now()->addWeeks(8)->toDateString(),
                'start_time' => '18:00:00',
                'end_time' => '22:00:00',
                'venue' => $venue->name,
                'event_type' => 'physical',
                'organizer_name' => $organizer->organization_name,
                'organizer_brand' => $organizer->organization_name,
                'total_capacity' => 600,
                'status' => Event::STATUS_PUBLISHED,
                'submitted_at' => now()->subDays(3),
                'approved_at' => now()->subDays(2),
                'is_featured' => true,
                'added_by' => 'system',
                'updated_by' => 'system',
            ]
        );

        $this->cleanupDemoState($event, $plan);

        app(SeatingPlanDesignService::class)->saveDesign($plan->refresh(), $this->design());

        $vipSection = SeatingSection::query()
            ->where('seating_plan_id', $plan->id)
            ->where('name', 'Buyer VIP Front')
            ->first();

        $standardSection = SeatingSection::query()
            ->where('seating_plan_id', $plan->id)
            ->where('name', 'Buyer Standard Middle')
            ->first();

        $vipTicket = EventTicket::query()->updateOrCreate(
            ['event_id' => $event->id, 'name' => 'Buyer VIP Reserved'],
            [
                'description' => 'VIP reserved-seat demo ticket for public visual buyer testing.',
                'ticket_type' => EventTicket::TYPE_PAID,
                'price' => 3000,
                'currency' => 'BDT',
                'quantity' => 80,
                'sold_quantity' => 1,
                'min_per_order' => 1,
                'max_per_order' => 4,
                'sales_start_at' => now()->subDay(),
                'sales_end_at' => now()->addWeeks(6),
                'visibility' => EventTicket::VISIBILITY_PUBLIC,
                'status' => EventTicket::STATUS_ACTIVE,
                'is_active' => true,
                'valid_section_ids' => $vipSection ? [$vipSection->id] : [],
                'platform_fee_type' => EventTicket::FEE_PERCENT,
                'platform_fee_value' => 5,
                'organizer_absorbs_fee' => false,
            ]
        );

        EventTicket::query()->updateOrCreate(
            ['event_id' => $event->id, 'name' => 'Buyer Standard Reserved'],
            [
                'description' => 'Standard reserved-seat demo ticket for public visual buyer testing.',
                'ticket_type' => EventTicket::TYPE_PAID,
                'price' => 1200,
                'currency' => 'BDT',
                'quantity' => 160,
                'sold_quantity' => 0,
                'min_per_order' => 1,
                'max_per_order' => 6,
                'sales_start_at' => now()->subDay(),
                'sales_end_at' => now()->addWeeks(6),
                'visibility' => EventTicket::VISIBILITY_PUBLIC,
                'status' => EventTicket::STATUS_ACTIVE,
                'is_active' => true,
                'valid_section_ids' => $standardSection ? [$standardSection->id] : [],
                'platform_fee_type' => EventTicket::FEE_PERCENT,
                'platform_fee_value' => 5,
                'organizer_absorbs_fee' => false,
            ]
        );

        $this->markDemoStates($event->refresh(), $vipTicket, $customer, $otherCustomer);
    }

    private function cleanupDemoState(Event $event, SeatingPlan $plan): void
    {
        SeatLock::query()->where('event_id', $event->id)->delete();
        CartItem::query()->where('event_id', $event->id)->delete();

        OrderTicket::query()->where('event_id', $event->id)->delete();
        OrderItem::query()->whereIn('order_id', $event->orders()->pluck('id'))->delete();
        $event->orders()->delete();

        $seatIds = $plan->seats()->pluck('seating_seats.id');
        if ($seatIds->isNotEmpty()) {
            SeatingSeat::query()->whereIn('id', $seatIds)->update([
                'is_disabled' => false,
                'is_researved' => false,
                'status' => 'available',
            ]);
        }
    }

    private function markDemoStates(Event $event, EventTicket $ticket, User $customer, User $otherCustomer): void
    {
        $event->loadMissing('seatingPlan.sections.seats');

        $seats = $event->seatingPlan->sections->flatMap->seats->keyBy('label');

        $disabledSeat = $seats->get('B1-4');
        if ($disabledSeat) {
            $disabledSeat->update(['is_disabled' => true, 'status' => 'disabled']);
        }

        $soldSeat = $seats->get('B1-1');
        if ($soldSeat) {
            $order = Order::query()->create([
                'user_id' => $customer->id,
                'session_id' => 'advanced-public-sold-session',
                'event_id' => $event->id,
                'order_number' => 'ADV-PUB-'.Str::upper(Str::random(8)),
                'subtotal' => $ticket->price,
                'discount_total' => 0,
                'fee_total' => 0,
                'total' => $ticket->price,
                'currency' => 'BDT',
                'status' => Order::STATUS_COMPLETED,
                'payment_status' => Order::PAYMENT_PAID,
                'customer_name' => $customer->name,
                'customer_email' => $customer->email,
            ]);

            $item = OrderItem::query()->create([
                'order_id' => $order->id,
                'event_ticket_id' => $ticket->id,
                'ticket_type_id' => $ticket->id,
                'ticket_name' => $ticket->name,
                'unit_price' => $ticket->price,
                'quantity' => 1,
                'subtotal' => $ticket->price,
            ]);

            OrderTicket::query()->create([
                'event_id' => $event->id,
                'order_id' => $order->id,
                'order_item_id' => $item->id,
                'event_ticket_id' => $ticket->id,
                'ticket_type_id' => $ticket->id,
                'seat_id' => $soldSeat->id,
                'ticket_code' => 'ADV-PUB-'.Str::upper(Str::random(10)),
                'qr_payload' => 'ADV-PUB-DEMO-'.$soldSeat->id,
                'attendee_name' => $customer->name,
                'attendee_email' => $customer->email,
                'status' => OrderTicket::STATUS_ISSUED,
            ]);
        }

        $lockedSeat = $seats->get('B1-2');
        if ($lockedSeat) {
            SeatLock::query()->create([
                'event_id' => $event->id,
                'seat_id' => $lockedSeat->id,
                'ticket_type_id' => $ticket->id,
                'user_id' => $otherCustomer->id,
                'session_id' => 'advanced-public-other-session',
                'expires_at' => now()->addMinutes(30),
            ]);
        }
    }

    private function design(): array
    {
        return [
            [
                'id' => 'buyer-stage-main',
                'type' => 'stage',
                'name' => 'Main Stage',
                'x' => 260,
                'y' => 30,
                'width' => 320,
                'height' => 80,
                'rotation' => 0,
                'capacity' => 0,
                'seats' => [],
            ],
            [
                'id' => 'buyer-vip-front',
                'type' => 'seat',
                'name' => 'Buyer VIP Front',
                'x' => 110,
                'y' => 150,
                'width' => 330,
                'height' => 200,
                'rotation' => 0,
                'capacity' => 12,
                'seats' => $this->seatGrid('B', 3, 4, 36, 48),
            ],
            [
                'id' => 'buyer-standard-middle',
                'type' => 'seat',
                'name' => 'Buyer Standard Middle',
                'x' => 480,
                'y' => 150,
                'width' => 330,
                'height' => 200,
                'rotation' => 0,
                'capacity' => 12,
                'seats' => $this->seatGrid('M', 3, 4, 36, 48),
            ],
            [
                'id' => 'buyer-standing-zone',
                'type' => 'general_admission',
                'name' => 'Standing Buyer Zone',
                'x' => 230,
                'y' => 410,
                'width' => 420,
                'height' => 150,
                'rotation' => 0,
                'capacity' => 250,
                'seats' => [],
            ],
        ];
    }

    private function seatGrid(string $prefix, int $rows, int $columns, int $size, int $gap): array
    {
        $seats = [];

        for ($row = 1; $row <= $rows; $row++) {
            $rowLabel = $prefix.$row;

            for ($column = 1; $column <= $columns; $column++) {
                $seats[] = [
                    'id' => Str::lower($prefix).'-'.$row.'-'.$column,
                    'label' => $rowLabel.'-'.$column,
                    'row_label' => $rowLabel,
                    'seat_number' => $column,
                    'x' => 24 + (($column - 1) * $gap),
                    'y' => 46 + (($row - 1) * $gap),
                    'width' => $size,
                    'height' => $size,
                    'disabled' => false,
                ];
            }
        }

        return $seats;
    }
}
