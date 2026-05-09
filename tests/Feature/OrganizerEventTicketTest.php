<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Event;
use App\Models\EventTicket;
use App\Models\EventType;
use App\Models\OrganizerProfile;
use App\Models\SeatingPlan;
use App\Models\SeatingSection;
use App\Models\User;
use App\Models\Venue;
use App\Services\TicketAvailabilityService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class OrganizerEventTicketTest extends TestCase
{
    use RefreshDatabase;

    public function test_approved_organizer_can_view_ticket_type_list_for_own_event(): void
    {
        [$user, $event] = $this->makeOrganizerEventStack();

        $this->actingAs($user, 'web')
            ->get(route('organizer.events.ticket-types.index', $event))
            ->assertSuccessful()
            ->assertSee('Ticket Types');
    }

    public function test_approved_organizer_can_create_ticket_type_for_own_event(): void
    {
        [$user, $event, $section] = $this->makeOrganizerEventStack('ticket-create@example.com');

        $this->actingAs($user, 'web')
            ->post(route('organizer.events.ticket-types.store', $event), $this->ticketPayload([
                'name' => 'Early Bird General',
                'valid_section_ids' => [$section->id],
            ]))
            ->assertRedirect();

        $this->assertDatabaseHas('event_tickets', [
            'event_id' => $event->id,
            'name' => 'Early Bird General',
            'ticket_type' => EventTicket::TYPE_PAID,
            'price' => 500,
            'currency' => 'BDT',
            'quantity' => 120,
            'status' => EventTicket::STATUS_ACTIVE,
        ]);
    }

    public function test_approved_organizer_can_edit_own_ticket_type(): void
    {
        [$user, $event] = $this->makeOrganizerEventStack('ticket-edit@example.com');
        $ticket = $this->makeTicket($event, 'Standard Ticket');

        $this->actingAs($user, 'web')
            ->put(route('organizer.events.ticket-types.update', [$event, $ticket]), $this->ticketPayload([
                'name' => 'Updated Standard Ticket',
                'price' => 700,
            ]))
            ->assertRedirect(route('organizer.events.ticket-types.show', [$event, $ticket]));

        $this->assertDatabaseHas('event_tickets', [
            'id' => $ticket->id,
            'name' => 'Updated Standard Ticket',
            'price' => 700,
        ]);
    }

    public function test_organizer_cannot_access_another_organizers_event_ticket_types(): void
    {
        [$owner, $event] = $this->makeOrganizerEventStack('ticket-owner@example.com');
        [$intruder] = $this->makeOrganizerEventStack('ticket-intruder@example.com');
        $ticket = $this->makeTicket($event, 'Owner Ticket');

        $this->actingAs($intruder, 'web')
            ->get(route('organizer.events.ticket-types.index', $event))
            ->assertForbidden();

        $this->actingAs($intruder, 'web')
            ->get(route('organizer.events.ticket-types.edit', [$event, $ticket]))
            ->assertForbidden();

        $this->assertDatabaseHas('event_tickets', [
            'id' => $ticket->id,
            'name' => 'Owner Ticket',
        ]);
    }

    public function test_pending_organizer_cannot_access_organizer_ticket_routes(): void
    {
        [$approvedUser, $event] = $this->makeOrganizerEventStack('ticket-approved@example.com');
        $pendingUser = $this->makeUserWithOrganizer('ticket-pending@example.com', OrganizerProfile::STATUS_PENDING);

        $this->actingAs($pendingUser, 'web')
            ->get(route('organizer.events.ticket-types.index', $event))
            ->assertRedirect();
    }

    public function test_ticket_availability_service_calculates_remaining_quantity_correctly(): void
    {
        [, $event] = $this->makeOrganizerEventStack('availability@example.com');
        $ticket = $this->makeTicket($event, 'Availability Ticket', [
            'quantity' => 100,
            'sold_quantity' => 35,
            'min_per_order' => 2,
            'max_per_order' => 5,
        ]);

        $summary = app(TicketAvailabilityService::class)->summary($ticket, 4);

        $this->assertSame(100, $summary['total_quantity']);
        $this->assertSame(35, $summary['sold_quantity']);
        $this->assertSame(65, $summary['remaining_quantity']);
        $this->assertTrue($summary['requested_quantity_allowed']);
    }

    public function test_admin_ticket_type_route_remains_registered(): void
    {
        $admin = Admin::create([
            'name' => 'Admin User',
            'email' => 'ticket-admin@example.com',
            'password' => Hash::make('password'),
        ]);
        [, $event] = $this->makeOrganizerEventStack('admin-ticket-route@example.com');

        $this->actingAs($admin, 'admin')
            ->get(route('admin.events.ticket-types.index', $event))
            ->assertSuccessful()
            ->assertJson(['status' => 'success']);
    }

    private function makeOrganizerEventStack(string $email = 'ticket-organizer@example.com'): array
    {
        $user = $this->makeUserWithOrganizer($email, OrganizerProfile::STATUS_APPROVED);
        $profile = $user->organizerProfile;

        $venue = Venue::create([
            'organizer_profile_id' => $profile->id,
            'organizer_id' => $user->id,
            'name' => 'Ticket Venue ' . md5($email),
            'city' => 'Dhaka',
            'country' => 'Bangladesh',
            'capacity' => 500,
        ]);

        $plan = SeatingPlan::create([
            'organizer_profile_id' => $profile->id,
            'venue_id' => $venue->id,
            'name' => 'Ticket Layout ' . md5($email),
            'status' => SeatingPlan::STATUS_ACTIVE,
        ]);

        $section = SeatingSection::create([
            'seating_plan_id' => $plan->id,
            'name' => 'Main Section ' . substr(md5($email), 0, 6),
            'type' => 'reserved',
            'capacity' => 120,
        ]);

        $type = EventType::create([
            'name' => 'Ticket Type ' . md5($email),
            'slug' => 'ticket-type-' . md5($email),
            'code' => 'TKT' . substr(md5($email), 0, 5),
            'status' => 'active',
        ]);

        $event = Event::create([
            'organizer_profile_id' => $profile->id,
            'event_type_id' => $type->id,
            'venue_id' => $venue->id,
            'seating_plan_id' => $plan->id,
            'name' => 'Ticket Event ' . md5($email),
            'slug' => 'ticket-event-' . md5($email),
            'venue' => $venue->name,
            'organizer_name' => $profile->organization_name,
            'description' => 'Ticket event description.',
            'start_date' => now()->addWeeks(2)->toDateString(),
            'end_date' => now()->addWeeks(2)->toDateString(),
            'status' => Event::STATUS_PUBLISHED,
            'approved_at' => now(),
        ]);

        return [$user->refresh(), $event, $section];
    }

    private function makeUserWithOrganizer(string $email, string $status): User
    {
        $user = User::create([
            'name' => 'Ticket Organizer',
            'email' => $email,
            'password' => Hash::make('password'),
        ]);

        $user->organizerProfile()->create([
            'organization_name' => 'Ticket Organizer ' . md5($email),
            'slug' => 'ticket-organizer-' . md5($email),
            'status' => $status,
            'submitted_at' => now(),
            'approved_at' => $status === OrganizerProfile::STATUS_APPROVED ? now() : null,
        ]);

        return $user->refresh();
    }

    private function makeTicket(Event $event, string $name, array $overrides = []): EventTicket
    {
        return EventTicket::create(array_merge([
            'event_id' => $event->id,
            'name' => $name,
            'description' => 'Test ticket.',
            'ticket_type' => EventTicket::TYPE_PAID,
            'price' => 500,
            'currency' => 'BDT',
            'quantity' => 120,
            'sold_quantity' => 0,
            'min_per_order' => 1,
            'max_per_order' => 6,
            'sales_start_at' => now()->subDay(),
            'sales_end_at' => now()->addWeek(),
            'visibility' => EventTicket::VISIBILITY_PUBLIC,
            'status' => EventTicket::STATUS_ACTIVE,
            'is_active' => true,
            'platform_fee_type' => EventTicket::FEE_NONE,
            'platform_fee_value' => 0,
            'organizer_absorbs_fee' => false,
        ], $overrides));
    }

    private function ticketPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'General Admission',
            'description' => 'Standard event access.',
            'ticket_type' => EventTicket::TYPE_PAID,
            'price' => 500,
            'currency' => 'BDT',
            'quantity' => 120,
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
}
