<?php

namespace Tests\Feature;

use App\Models\CartItem;
use App\Models\Event;
use App\Models\EventTicket;
use App\Models\EventType;
use App\Models\OrganizerProfile;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderTicket;
use App\Models\SeatLock;
use App\Models\SeatingPlan;
use App\Models\SeatingSeat;
use App\Models\SeatingSection;
use App\Models\User;
use App\Models\Venue;
use App\Services\Seating\SeatingPlanDesignService;
use Database\Seeders\AdvancedPublicSeatSelectionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class PublicVisualSeatSelectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_visual_map_renders_saved_designer_layout(): void
    {
        $this->seed(AdvancedPublicSeatSelectionSeeder::class);

        $event = Event::where('slug', 'advanced-public-seat-selection-demo')->firstOrFail();

        $this->get(route('frontend.seats.select', $event))
            ->assertOk()
            ->assertSee('visual-seat-map')
            ->assertSee('Buyer VIP Front')
            ->assertSee('Buyer Standard Middle')
            ->assertSee('Main Stage')
            ->assertSee('Standing Buyer Zone')
            ->assertSee('data-ticket-ids', false);
    }

    public function test_available_visual_seat_can_be_locked_and_unlocked_by_owner(): void
    {
        $this->seed(AdvancedPublicSeatSelectionSeeder::class);

        $customer = User::where('email', 'advanced.public.customer@example.com')->firstOrFail();
        $event = Event::where('slug', 'advanced-public-seat-selection-demo')->firstOrFail();
        $ticket = EventTicket::where('event_id', $event->id)->where('name', 'Buyer Standard Reserved')->firstOrFail();
        $seat = SeatingSeat::whereHas('section', fn ($query) => $query->where('seating_plan_id', $event->seating_plan_id))
            ->where('label', 'M1-1')
            ->firstOrFail();

        $this->actingAs($customer)
            ->postJson(route('frontend.seats.lock', $event), [
                'event_ticket_id' => $ticket->id,
                'seat_id' => $seat->id,
            ])
            ->assertOk()
            ->assertJsonPath('status', 'success');

        $this->assertDatabaseHas('seat_locks', [
            'event_id' => $event->id,
            'seat_id' => $seat->id,
            'user_id' => $customer->id,
        ]);

        $this->assertDatabaseHas('cart_items', [
            'event_id' => $event->id,
            'seat_id' => $seat->id,
            'user_id' => $customer->id,
        ]);

        $this->actingAs($customer)
            ->get(route('frontend.seats.select', $event))
            ->assertOk()
            ->assertSee('Selected Seats')
            ->assertSee('M1-1');

        $this->actingAs($customer)
            ->postJson(route('frontend.seats.unlock', $event), [
                'seat_id' => $seat->id,
            ])
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

    public function test_other_user_cannot_unlock_another_customers_selected_seat(): void
    {
        $this->seed(AdvancedPublicSeatSelectionSeeder::class);

        $owner = User::where('email', 'advanced.public.customer@example.com')->firstOrFail();
        $other = User::factory()->create();
        $event = Event::where('slug', 'advanced-public-seat-selection-demo')->firstOrFail();
        $ticket = EventTicket::where('event_id', $event->id)->where('name', 'Buyer Standard Reserved')->firstOrFail();
        $seat = SeatingSeat::whereHas('section', fn ($query) => $query->where('seating_plan_id', $event->seating_plan_id))
            ->where('label', 'M1-2')
            ->firstOrFail();

        $this->actingAs($owner)->postJson(route('frontend.seats.lock', $event), [
            'event_ticket_id' => $ticket->id,
            'seat_id' => $seat->id,
        ])->assertOk();

        $this->actingAs($other)
            ->postJson(route('frontend.seats.unlock', $event), ['seat_id' => $seat->id])
            ->assertForbidden()
            ->assertJsonPath('status', 'error');

        $this->assertDatabaseHas('seat_locks', [
            'event_id' => $event->id,
            'seat_id' => $seat->id,
            'user_id' => $owner->id,
        ]);
    }

    public function test_sold_disabled_locked_and_wrong_section_seats_cannot_be_selected(): void
    {
        $this->seed(AdvancedPublicSeatSelectionSeeder::class);

        $customer = User::where('email', 'advanced.public.customer@example.com')->firstOrFail();
        $event = Event::where('slug', 'advanced-public-seat-selection-demo')->firstOrFail();
        $vipTicket = EventTicket::where('event_id', $event->id)->where('name', 'Buyer VIP Reserved')->firstOrFail();
        $standardTicket = EventTicket::where('event_id', $event->id)->where('name', 'Buyer Standard Reserved')->firstOrFail();
        $seats = SeatingSeat::whereHas('section', fn ($query) => $query->where('seating_plan_id', $event->seating_plan_id))->get()->keyBy('label');

        foreach (['B1-1', 'B1-2', 'B1-4'] as $blockedLabel) {
            $this->actingAs($customer)
                ->postJson(route('frontend.seats.lock', $event), [
                    'event_ticket_id' => $vipTicket->id,
                    'seat_id' => $seats->get($blockedLabel)->id,
                ])
                ->assertStatus(422);
        }

        $this->actingAs($customer)
            ->postJson(route('frontend.seats.lock', $event), [
                'event_ticket_id' => $standardTicket->id,
                'seat_id' => $seats->get('B2-1')->id,
            ])
            ->assertStatus(422);
    }

    public function test_old_plan_without_design_json_still_renders_list_fallback(): void
    {
        [$event, $ticket, $seat] = $this->createFallbackEvent();

        $this->get(route('frontend.seats.select', $event))
            ->assertOk()
            ->assertSee('fallback-seat-list')
            ->assertSee($seat->label)
            ->assertDontSee('visual-seat-map');

        $this->postJson(route('frontend.seats.lock', $event), [
            'event_ticket_id' => $ticket->id,
            'seat_id' => $seat->id,
        ])
            ->assertOk()
            ->assertJsonPath('status', 'success');
    }

    public function test_route_safety_still_avoids_old_architecture(): void
    {
        $blocked = ['TemporaryBooking', 'TemporaryBookingSeat', 'EventSeatType', 'BookingController', 'EventSeatController', 'EventSeatTypeController', 'ClearExpiredTemporaryBookings'];
        $routes = collect(Route::getRoutes())->map(fn ($route) => $route->uri().' '.$route->getActionName())->implode('\n');

        foreach ($blocked as $term) {
            $this->assertStringNotContainsString($term, $routes);
        }
    }

    private function createFallbackEvent(): array
    {
        $user = User::factory()->create();
        $organizer = OrganizerProfile::create([
            'user_id' => $user->id,
            'organization_name' => 'Fallback Organizer',
            'slug' => 'fallback-organizer-'.uniqid(),
            'status' => OrganizerProfile::STATUS_APPROVED,
            'approved_at' => now(),
        ]);

        $type = EventType::create([
            'name' => 'Fallback Type',
            'slug' => 'fallback-type-'.uniqid(),
            'status' => 'active',
        ]);

        $venue = Venue::create([
            'organizer_profile_id' => $organizer->id,
            'organizer_id' => $organizer->user_id,
            'name' => 'Fallback Venue',
            'slug' => 'fallback-venue-'.uniqid(),
            'city' => 'Dhaka',
        ]);

        $plan = SeatingPlan::create([
            'organizer_profile_id' => $organizer->id,
            'venue_id' => $venue->id,
            'name' => 'Fallback List Plan',
            'status' => SeatingPlan::STATUS_ACTIVE,
            'design_json' => null,
        ]);

        $section = SeatingSection::create([
            'seating_plan_id' => $plan->id,
            'name' => 'Fallback Section',
            'type' => 'seat',
            'capacity' => 1,
            'x' => 0,
            'y' => 0,
            'rotation' => 0,
        ]);

        $seat = SeatingSeat::create([
            'section_id' => $section->id,
            'label' => 'F1',
            'row_label' => 'F',
            'seat_number' => 1,
            'x' => 0,
            'y' => 0,
            'status' => 'available',
        ]);

        $event = Event::create([
            'organizer_profile_id' => $organizer->id,
            'event_type_id' => $type->id,
            'venue_id' => $venue->id,
            'seating_plan_id' => $plan->id,
            'name' => 'Fallback Seat Event '.uniqid(),
            'description' => 'Fallback visual seat selection test.',
            'start_date' => now()->addWeek()->toDateString(),
            'end_date' => now()->addWeek()->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'venue' => $venue->name,
            'organizer_name' => $organizer->organization_name,
            'status' => Event::STATUS_PUBLISHED,
            'approved_at' => now(),
        ]);

        $ticket = EventTicket::create([
            'event_id' => $event->id,
            'name' => 'Fallback Reserved',
            'description' => 'Fallback reserved ticket.',
            'ticket_type' => EventTicket::TYPE_PAID,
            'price' => 100,
            'currency' => 'BDT',
            'quantity' => 10,
            'sold_quantity' => 0,
            'min_per_order' => 1,
            'max_per_order' => 5,
            'sales_start_at' => now()->subDay(),
            'sales_end_at' => now()->addDays(10),
            'visibility' => EventTicket::VISIBILITY_PUBLIC,
            'status' => EventTicket::STATUS_ACTIVE,
            'valid_section_ids' => [$section->id],
            'platform_fee_type' => EventTicket::FEE_NONE,
            'platform_fee_value' => 0,
            'organizer_absorbs_fee' => false,
            'is_active' => true,
        ]);

        return [$event, $ticket, $seat];
    }
}
