<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\CartItem;
use App\Models\Event;
use App\Models\EventTicket;
use App\Models\EventType;
use App\Models\OrganizerProfile;
use App\Models\SeatLock;
use App\Models\SeatingPlan;
use App\Models\SeatingSeat;
use App\Models\SeatingSection;
use App\Models\User;
use App\Models\Venue;
use Database\Seeders\AdvancedPublicSeatSelectionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AdvancedSeatMapProductionHardeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_designer_save_rebuilds_sections_seats_and_preserves_ticket_matrix_assignments(): void
    {
        $admin = Admin::create([
            'name' => 'A7 Admin',
            'email' => 'a7-admin@example.com',
            'password' => Hash::make('password'),
        ]);

        [, $event, $vipSection] = $this->makeVisualEventStack('a7-admin-owner@example.com');

        $ticket = EventTicket::create($this->ticketPayload($event, 'A7 VIP Ticket', [$vipSection->id], 2000));
        $oldVipSectionId = $vipSection->id;

        $this->actingAs($admin, 'admin')
            ->postJson(route('admin.seating-plans.designer.save', $event->seating_plan_id), [
                'design_json' => $this->designerPayload(3),
            ])
            ->assertOk()
            ->assertJsonPath('status', 'success');

        $plan = $event->seatingPlan()->with('sections.seats')->firstOrFail();
        $newVipSection = $plan->sections->firstWhere('name', 'A7 VIP Section');

        $this->assertNotNull($newVipSection);
        $this->assertNotSame($oldVipSectionId, $newVipSection->id);
        $this->assertSame(2, $plan->sections()->count());
        $this->assertSame(6, $plan->seats()->count());
        $this->assertSame([$newVipSection->id], $ticket->fresh()->validSectionIdsArray());
    }

    public function test_organizer_designer_save_is_limited_to_owned_plans(): void
    {
        [$owner, $event] = $this->makeVisualEventStack('a7-owner@example.com');
        [$intruder] = $this->makeVisualEventStack('a7-intruder@example.com');

        $this->actingAs($owner, 'web')
            ->get(route('organizer.seating-plans.designer', $event->seating_plan_id))
            ->assertOk();

        $this->actingAs($owner, 'web')
            ->postJson(route('organizer.seating-plans.designer.save', $event->seating_plan_id), [
                'design_json' => $this->designerPayload(2),
            ])
            ->assertOk()
            ->assertJsonPath('status', 'success');

        $this->actingAs($intruder, 'web')
            ->postJson(route('organizer.seating-plans.designer.save', $event->seating_plan_id), [
                'design_json' => $this->designerPayload(2),
            ])
            ->assertForbidden();
    }

    public function test_repeated_designer_save_keeps_rebuilt_inventory_deduplicated(): void
    {
        [$organizer, $event] = $this->makeVisualEventStack('a7-repeat@example.com');
        $payload = $this->designerPayload(4);

        $this->actingAs($organizer, 'web')
            ->postJson(route('organizer.seating-plans.designer.save', $event->seating_plan_id), ['design_json' => $payload])
            ->assertOk();

        $this->actingAs($organizer, 'web')
            ->postJson(route('organizer.seating-plans.designer.save', $event->seating_plan_id), ['design_json' => $payload])
            ->assertOk();

        $plan = $event->seatingPlan()->firstOrFail();

        $this->assertSame(2, $plan->sections()->count());
        $this->assertSame(8, $plan->seats()->count());
    }

    public function test_public_visual_map_shows_sold_locked_unavailable_and_ticket_allowed_states(): void
    {
        $this->seed(AdvancedPublicSeatSelectionSeeder::class);

        $event = Event::where('slug', 'advanced-public-seat-selection-demo')->firstOrFail();
        $vipTicket = EventTicket::where('event_id', $event->id)->where('name', 'Buyer VIP Reserved')->firstOrFail();

        $this->get(route('frontend.seats.select', ['event' => $event, 'ticket' => $vipTicket->id]))
            ->assertOk()
            ->assertSee('id="visual-seat-map"', false)
            ->assertSee('Buyer VIP Front')
            ->assertSee('data-status="sold"', false)
            ->assertSee('data-status="locked"', false)
            ->assertSee('data-status="unavailable"', false)
            ->assertSee('data-ticket-ids', false);
    }

    public function test_ticket_section_assignment_is_enforced_during_public_locking(): void
    {
        [, $event, $vipSection, $standardSection] = $this->makeVisualEventStack('a7-public-matrix@example.com', true);
        $customer = User::create([
            'name' => 'A7 Customer',
            'email' => 'a7-customer@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $vipTicket = EventTicket::create($this->ticketPayload($event, 'A7 VIP Only', [$vipSection->id], 2500));
        $standardSeat = SeatingSeat::where('section_id', $standardSection->id)->firstOrFail();
        $vipSeat = SeatingSeat::where('section_id', $vipSection->id)->firstOrFail();

        $this->actingAs($customer, 'web')
            ->postJson(route('frontend.seats.lock', $event), [
                'event_ticket_id' => $vipTicket->id,
                'seat_id' => $standardSeat->id,
            ])
            ->assertStatus(422);

        $this->actingAs($customer, 'web')
            ->postJson(route('frontend.seats.lock', $event), [
                'event_ticket_id' => $vipTicket->id,
                'seat_id' => $vipSeat->id,
            ])
            ->assertOk()
            ->assertJsonPath('status', 'success');
    }

    public function test_owner_only_unlock_and_cart_cleanup_are_enforced(): void
    {
        [, $event, $vipSection] = $this->makeVisualEventStack('a7-unlock@example.com', true);
        $owner = User::create([
            'name' => 'A7 Seat Owner',
            'email' => 'a7-seat-owner@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $other = User::create([
            'name' => 'A7 Other Customer',
            'email' => 'a7-other-customer@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $ticket = EventTicket::create($this->ticketPayload($event, 'A7 Owner Ticket', [$vipSection->id], 2000));
        $seat = SeatingSeat::where('section_id', $vipSection->id)->firstOrFail();

        $this->actingAs($owner, 'web')
            ->postJson(route('frontend.seats.lock', $event), [
                'event_ticket_id' => $ticket->id,
                'seat_id' => $seat->id,
            ])
            ->assertOk();

        $this->assertDatabaseHas('cart_items', [
            'event_id' => $event->id,
            'seat_id' => $seat->id,
            'user_id' => $owner->id,
        ]);

        $this->actingAs($other, 'web')
            ->postJson(route('frontend.seats.unlock', $event), ['seat_id' => $seat->id])
            ->assertForbidden();

        $this->assertDatabaseHas('seat_locks', [
            'event_id' => $event->id,
            'seat_id' => $seat->id,
            'user_id' => $owner->id,
        ]);

        $this->actingAs($owner, 'web')
            ->postJson(route('frontend.seats.unlock', $event), ['seat_id' => $seat->id])
            ->assertOk()
            ->assertJsonPath('status', 'success');

        $this->assertDatabaseMissing('seat_locks', [
            'event_id' => $event->id,
            'seat_id' => $seat->id,
        ]);
        $this->assertDatabaseMissing('cart_items', [
            'event_id' => $event->id,
            'seat_id' => $seat->id,
        ]);
    }

    public function test_relocking_same_owned_seat_with_another_ticket_keeps_one_cart_item(): void
    {
        [, $event, $vipSection] = $this->makeVisualEventStack('a7-cart-consistency@example.com', true);
        $customer = User::create([
            'name' => 'A7 Cart Customer',
            'email' => 'a7-cart-customer@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $allTicket = EventTicket::create($this->ticketPayload($event, 'A7 Any Section', [], 1000));
        $vipTicket = EventTicket::create($this->ticketPayload($event, 'A7 VIP Section', [$vipSection->id], 2500));
        $seat = SeatingSeat::where('section_id', $vipSection->id)->firstOrFail();

        $this->actingAs($customer, 'web')
            ->postJson(route('frontend.seats.lock', $event), [
                'event_ticket_id' => $allTicket->id,
                'seat_id' => $seat->id,
            ])
            ->assertOk();

        $this->actingAs($customer, 'web')
            ->postJson(route('frontend.seats.lock', $event), [
                'event_ticket_id' => $vipTicket->id,
                'seat_id' => $seat->id,
            ])
            ->assertOk();

        $this->assertSame(1, CartItem::where('event_id', $event->id)->where('seat_id', $seat->id)->count());
        $this->assertDatabaseHas('cart_items', [
            'event_id' => $event->id,
            'seat_id' => $seat->id,
            'ticket_type_id' => $vipTicket->id,
            'user_id' => $customer->id,
        ]);
        $this->assertSame(1, SeatLock::where('event_id', $event->id)->where('seat_id', $seat->id)->count());
    }

    public function test_route_safety_still_avoids_old_architecture(): void
    {
        $blocked = ['Booking', 'TemporaryBooking', 'TemporaryBookingSeat', 'EventSeat', 'EventSeatType', 'BookingController', 'EventSeatController', 'EventSeatTypeController', 'ClearExpiredTemporaryBookings'];
        $routes = collect(Route::getRoutes())->map(fn ($route) => $route->uri().' '.$route->getActionName())->implode('\n');

        foreach ($blocked as $term) {
            $this->assertStringNotContainsString($term, $routes);
        }
    }

    private function makeVisualEventStack(string $email, bool $published = false): array
    {
        $user = User::create([
            'name' => 'A7 Organizer',
            'email' => $email,
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $profile = $user->organizerProfile()->create([
            'organization_name' => 'A7 Organizer '.md5($email),
            'slug' => 'a7-organizer-'.md5($email),
            'email' => $user->email,
            'status' => OrganizerProfile::STATUS_APPROVED,
            'submitted_at' => now(),
            'approved_at' => now(),
        ]);

        $venue = Venue::create([
            'organizer_profile_id' => $profile->id,
            'organizer_id' => $user->id,
            'name' => 'A7 Venue '.md5($email),
            'slug' => 'a7-venue-'.md5($email),
            'city' => 'Dhaka',
            'country' => 'Bangladesh',
            'capacity' => 500,
        ]);

        $plan = SeatingPlan::create([
            'organizer_profile_id' => $profile->id,
            'venue_id' => $venue->id,
            'name' => 'A7 Plan '.md5($email),
            'status' => SeatingPlan::STATUS_ACTIVE,
            'design_json' => $this->designerPayload(2),
        ]);

        $vipSection = SeatingSection::create([
            'seating_plan_id' => $plan->id,
            'name' => 'A7 VIP Section',
            'type' => 'seat',
            'capacity' => 2,
            'x' => 120,
            'y' => 120,
        ]);

        $standardSection = SeatingSection::create([
            'seating_plan_id' => $plan->id,
            'name' => 'A7 Standard Section',
            'type' => 'seat',
            'capacity' => 2,
            'x' => 420,
            'y' => 120,
        ]);

        foreach ([$vipSection, $standardSection] as $section) {
            for ($i = 1; $i <= 2; $i++) {
                SeatingSeat::create([
                    'section_id' => $section->id,
                    'label' => ($section->id === $vipSection->id ? 'A7V' : 'A7S').'-'.$i,
                    'row_label' => $section->id === $vipSection->id ? 'A7V' : 'A7S',
                    'seat_number' => $i,
                    'x' => 20 + ($i * 40),
                    'y' => 40,
                    'status' => 'available',
                ]);
            }
        }

        $type = EventType::create([
            'name' => 'A7 Type '.md5($email),
            'slug' => 'a7-type-'.md5($email),
            'code' => 'A7'.substr(md5($email), 0, 5),
            'status' => 'active',
        ]);

        $event = Event::create([
            'organizer_profile_id' => $profile->id,
            'event_type_id' => $type->id,
            'venue_id' => $venue->id,
            'seating_plan_id' => $plan->id,
            'name' => 'A7 Event '.md5($email),
            'slug' => 'a7-event-'.md5($email),
            'venue' => $venue->name,
            'organizer_name' => $profile->organization_name,
            'description' => 'A7 hardening event.',
            'start_date' => now()->addMonth()->toDateString(),
            'end_date' => now()->addMonth()->toDateString(),
            'start_time' => '18:00:00',
            'end_time' => '21:00:00',
            'status' => $published ? Event::STATUS_PUBLISHED : Event::STATUS_DRAFT,
            'event_type' => 'physical',
        ]);

        return [$user, $event, $vipSection, $standardSection];
    }

    private function ticketPayload(Event $event, string $name, array $sectionIds, int $price): array
    {
        return [
            'event_id' => $event->id,
            'name' => $name,
            'description' => $name.' description.',
            'ticket_type' => EventTicket::TYPE_PAID,
            'price' => $price,
            'currency' => 'BDT',
            'quantity' => 50,
            'sold_quantity' => 0,
            'min_per_order' => 1,
            'max_per_order' => 6,
            'sales_start_at' => now()->subDay(),
            'sales_end_at' => now()->addWeeks(4),
            'visibility' => EventTicket::VISIBILITY_PUBLIC,
            'status' => EventTicket::STATUS_ACTIVE,
            'is_active' => true,
            'valid_section_ids' => $sectionIds,
            'platform_fee_type' => EventTicket::FEE_PERCENT,
            'platform_fee_value' => 5,
            'organizer_absorbs_fee' => false,
        ];
    }

    private function designerPayload(int $seatsPerSection): array
    {
        return [
            [
                'id' => 'a7-stage',
                'type' => 'stage',
                'name' => 'A7 Main Stage',
                'x' => 240,
                'y' => 30,
                'width' => 300,
                'height' => 70,
                'capacity' => 0,
                'seats' => [],
            ],
            [
                'id' => 'a7-vip',
                'type' => 'seat',
                'name' => 'A7 VIP Section',
                'x' => 120,
                'y' => 140,
                'width' => 280,
                'height' => 150,
                'capacity' => $seatsPerSection,
                'seats' => $this->seatRows('A7V', $seatsPerSection),
            ],
            [
                'id' => 'a7-standard',
                'type' => 'seat',
                'name' => 'A7 Standard Section',
                'x' => 460,
                'y' => 140,
                'width' => 280,
                'height' => 150,
                'capacity' => $seatsPerSection,
                'seats' => $this->seatRows('A7S', $seatsPerSection),
            ],
        ];
    }

    private function seatRows(string $prefix, int $count): array
    {
        $seats = [];

        for ($i = 1; $i <= $count; $i++) {
            $seats[] = [
                'id' => strtolower($prefix).'-'.$i,
                'label' => $prefix.'-'.$i,
                'row_label' => $prefix,
                'seat_number' => $i,
                'x' => 20 + (($i - 1) * 42),
                'y' => 40,
                'disabled' => false,
            ];
        }

        return $seats;
    }
}
