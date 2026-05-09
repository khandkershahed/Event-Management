<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Event;
use App\Models\EventTicket;
use App\Models\EventType;
use App\Models\OrganizerProfile;
use App\Models\SeatingPlan;
use App\Models\SeatingSeat;
use App\Models\SeatingSection;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AdvancedTicketSectionMatrixTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_assign_ticket_type_to_selected_seating_sections(): void
    {
        $admin = Admin::create([
            'name' => 'Matrix Admin',
            'email' => 'matrix-admin@example.com',
            'password' => Hash::make('password'),
        ]);

        [, $event, $vipSection] = $this->makeEventStack('admin-matrix@example.com');

        $this->actingAs($admin, 'admin')
            ->postJson(route('admin.events.ticket-types.store', $event), $this->adminPayload([
                'name' => 'Admin VIP Matrix',
                'section_ids' => [$vipSection->id],
            ]))
            ->assertOk()
            ->assertJsonPath('status', 'success');

        $ticket = EventTicket::where('event_id', $event->id)->where('name', 'Admin VIP Matrix')->firstOrFail();

        $this->assertSame([$vipSection->id], $ticket->validSectionIdsArray());
    }

    public function test_admin_rejects_section_ids_outside_event_seating_plan(): void
    {
        $admin = Admin::create([
            'name' => 'Matrix Admin Invalid',
            'email' => 'matrix-admin-invalid@example.com',
            'password' => Hash::make('password'),
        ]);

        [, $event] = $this->makeEventStack('admin-invalid-owner@example.com');
        [, , $foreignSection] = $this->makeEventStack('admin-invalid-foreign@example.com');

        $this->actingAs($admin, 'admin')
            ->postJson(route('admin.events.ticket-types.store', $event), $this->adminPayload([
                'name' => 'Invalid Section Matrix',
                'section_ids' => [$foreignSection->id],
            ]))
            ->assertStatus(422)
            ->assertJsonPath('status', 'validation_error');

        $this->assertDatabaseMissing('event_tickets', [
            'event_id' => $event->id,
            'name' => 'Invalid Section Matrix',
        ]);
    }

    public function test_organizer_can_assign_ticket_type_to_own_event_sections(): void
    {
        [$organizer, $event, $vipSection] = $this->makeEventStack('organizer-matrix@example.com');

        $this->actingAs($organizer, 'web')
            ->post(route('organizer.events.ticket-types.store', $event), $this->organizerPayload([
                'name' => 'Organizer VIP Matrix',
                'valid_section_ids' => [$vipSection->id],
            ]))
            ->assertRedirect();

        $ticket = EventTicket::where('event_id', $event->id)->where('name', 'Organizer VIP Matrix')->firstOrFail();

        $this->assertSame([$vipSection->id], $ticket->validSectionIdsArray());
    }

    public function test_organizer_cannot_assign_another_organizers_sections(): void
    {
        [$organizer, $event] = $this->makeEventStack('organizer-own-matrix@example.com');
        [, , $foreignSection] = $this->makeEventStack('organizer-foreign-matrix@example.com');

        $this->actingAs($organizer, 'web')
            ->from(route('organizer.events.ticket-types.create', $event))
            ->post(route('organizer.events.ticket-types.store', $event), $this->organizerPayload([
                'name' => 'Organizer Invalid Matrix',
                'valid_section_ids' => [$foreignSection->id],
            ]))
            ->assertRedirect(route('organizer.events.ticket-types.create', $event))
            ->assertSessionHasErrors('valid_section_ids');

        $this->assertDatabaseMissing('event_tickets', [
            'event_id' => $event->id,
            'name' => 'Organizer Invalid Matrix',
        ]);
    }

    public function test_public_visual_seat_selection_respects_ticket_section_assignments(): void
    {
        [$customer, $event, $vipSection, $standardSection] = $this->makeEventStack('public-matrix@example.com', true);

        $vipSeat = SeatingSeat::where('section_id', $vipSection->id)->firstOrFail();
        $standardSeat = SeatingSeat::where('section_id', $standardSection->id)->firstOrFail();

        $vipTicket = EventTicket::create($this->ticketRecord($event, 'VIP Only Matrix Ticket', [$vipSection->id]));

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

    public function test_route_safety_still_avoids_old_architecture(): void
    {
        $blocked = ['TemporaryBooking', 'TemporaryBookingSeat', 'EventSeatType', 'BookingController', 'EventSeatController', 'EventSeatTypeController', 'ClearExpiredTemporaryBookings'];
        $routes = collect(Route::getRoutes())->map(fn ($route) => $route->uri().' '.$route->getActionName())->implode('\n');

        foreach ($blocked as $term) {
            $this->assertStringNotContainsString($term, $routes);
        }
    }

    private function makeEventStack(string $email, bool $customerFirst = false): array
    {
        $organizerUser = User::create([
            'name' => 'Matrix Organizer',
            'email' => $email,
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $profile = $organizerUser->organizerProfile()->create([
            'organization_name' => 'Matrix Organizer '.md5($email),
            'slug' => 'matrix-organizer-'.md5($email),
            'email' => $organizerUser->email,
            'status' => OrganizerProfile::STATUS_APPROVED,
            'submitted_at' => now(),
            'approved_at' => now(),
        ]);

        $venue = Venue::create([
            'organizer_profile_id' => $profile->id,
            'organizer_id' => $organizerUser->id,
            'name' => 'Matrix Venue '.md5($email),
            'slug' => 'matrix-venue-'.md5($email),
            'city' => 'Dhaka',
            'country' => 'Bangladesh',
            'capacity' => 500,
        ]);

        $plan = SeatingPlan::create([
            'organizer_profile_id' => $profile->id,
            'venue_id' => $venue->id,
            'name' => 'Matrix Plan '.md5($email),
            'status' => SeatingPlan::STATUS_ACTIVE,
        ]);

        $vipSection = SeatingSection::create([
            'seating_plan_id' => $plan->id,
            'name' => 'Matrix VIP '.substr(md5($email), 0, 6),
            'type' => 'reserved',
            'capacity' => 10,
        ]);

        $standardSection = SeatingSection::create([
            'seating_plan_id' => $plan->id,
            'name' => 'Matrix Standard '.substr(md5($email), 0, 6),
            'type' => 'reserved',
            'capacity' => 10,
        ]);

        foreach ([$vipSection, $standardSection] as $section) {
            SeatingSeat::create([
                'section_id' => $section->id,
                'label' => $section->id.'-A1',
                'row_label' => 'A',
                'seat_number' => 1,
                'x' => 0,
                'y' => 0,
                'status' => 'available',
            ]);
        }

        $type = EventType::create([
            'name' => 'Matrix Type '.md5($email),
            'slug' => 'matrix-type-'.md5($email),
            'code' => 'MT'.substr(md5($email), 0, 5),
            'status' => 'active',
        ]);

        $event = Event::create([
            'organizer_profile_id' => $profile->id,
            'event_type_id' => $type->id,
            'venue_id' => $venue->id,
            'seating_plan_id' => $plan->id,
            'name' => 'Matrix Event '.md5($email),
            'slug' => 'matrix-event-'.md5($email),
            'venue' => $venue->name,
            'organizer_name' => $profile->organization_name,
            'description' => 'Ticket section matrix test event.',
            'start_date' => now()->addWeeks(2)->toDateString(),
            'end_date' => now()->addWeeks(2)->toDateString(),
            'start_time' => '18:00:00',
            'end_time' => '22:00:00',
            'status' => Event::STATUS_PUBLISHED,
            'approved_at' => now(),
        ]);

        if ($customerFirst) {
            $customer = User::create([
                'name' => 'Matrix Customer',
                'email' => 'customer-'.$email,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);

            return [$customer, $event, $vipSection, $standardSection];
        }

        return [$organizerUser->refresh(), $event, $vipSection, $standardSection];
    }

    private function adminPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Matrix Ticket',
            'description' => 'Admin matrix ticket.',
            'price' => 500,
            'quantity' => 100,
            'section_ids' => [],
            'is_active' => 1,
            'min_per_order' => 1,
            'max_per_order' => 6,
            'platform_fee_fixed' => 0,
            'platform_fee_percent' => 0,
            'processing_fee_fixed' => 0,
            'processing_fee_percent' => 0,
            'payment_gateway_fee_fixed' => 0,
            'payment_gateway_fee_percent' => 0,
            'fee_customer_percent' => 100,
            'fee_organizer_percent' => 0,
        ], $overrides);
    }

    private function organizerPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Organizer Matrix Ticket',
            'description' => 'Organizer matrix ticket.',
            'ticket_type' => EventTicket::TYPE_PAID,
            'price' => 500,
            'currency' => 'BDT',
            'quantity' => 100,
            'min_per_order' => 1,
            'max_per_order' => 6,
            'sales_start_at' => now()->subDay()->format('Y-m-d H:i:s'),
            'sales_end_at' => now()->addWeek()->format('Y-m-d H:i:s'),
            'visibility' => EventTicket::VISIBILITY_PUBLIC,
            'status' => EventTicket::STATUS_ACTIVE,
            'platform_fee_type' => EventTicket::FEE_NONE,
            'platform_fee_value' => 0,
            'organizer_absorbs_fee' => false,
            'valid_section_ids' => [],
        ], $overrides);
    }

    private function ticketRecord(Event $event, string $name, array $sectionIds): array
    {
        return [
            'event_id' => $event->id,
            'name' => $name,
            'description' => 'Public matrix lock test ticket.',
            'ticket_type' => EventTicket::TYPE_PAID,
            'price' => 500,
            'currency' => 'BDT',
            'quantity' => 100,
            'sold_quantity' => 0,
            'min_per_order' => 1,
            'max_per_order' => 6,
            'sales_start_at' => now()->subDay(),
            'sales_end_at' => now()->addWeek(),
            'visibility' => EventTicket::VISIBILITY_PUBLIC,
            'status' => EventTicket::STATUS_ACTIVE,
            'is_active' => true,
            'valid_section_ids' => $sectionIds,
            'platform_fee_type' => EventTicket::FEE_NONE,
            'platform_fee_value' => 0,
            'organizer_absorbs_fee' => false,
        ];
    }
}
