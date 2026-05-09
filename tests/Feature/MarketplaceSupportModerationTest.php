<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\AuditLog;
use App\Models\Event;
use App\Models\MarketplaceModerationFlag;
use App\Models\MarketplaceSupportTicket;
use App\Models\Order;
use App\Models\OrganizerProfile;
use App\Models\User;
use App\Notifications\SupportTicketUpdateNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class MarketplaceSupportModerationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['mail.default' => 'array']);
    }

    public function test_customer_can_create_view_and_reply_to_own_support_ticket(): void
    {
        $customer = User::factory()->create();
        $event = $this->eventForOrganizer()['event'];
        $order = $this->orderFor($customer, $event);

        $response = $this->actingAs($customer)->post(route('user.support-tickets.store'), [
            'type' => MarketplaceSupportTicket::TYPE_ORDER,
            'priority' => MarketplaceSupportTicket::PRIORITY_NORMAL,
            'subject' => 'Need help with my order',
            'description' => 'I need support for this order.',
            'order_id' => $order->id,
        ]);

        $ticket = MarketplaceSupportTicket::first();
        $response->assertRedirect(route('user.support-tickets.show', $ticket));

        $this->actingAs($customer)
            ->get(route('user.support-tickets.show', $ticket))
            ->assertOk()
            ->assertSee('Need help with my order');

        $this->actingAs($customer)
            ->post(route('user.support-tickets.reply', $ticket), ['body' => 'Here is more information.'])
            ->assertRedirect(route('user.support-tickets.show', $ticket));

        $this->assertDatabaseHas('marketplace_support_messages', [
            'marketplace_support_ticket_id' => $ticket->id,
            'body' => 'Here is more information.',
        ]);
    }

    public function test_customer_cannot_view_another_customer_ticket(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $ticket = $this->customerTicket($owner);

        $this->actingAs($other)
            ->get(route('user.support-tickets.show', $ticket))
            ->assertNotFound();
    }

    public function test_organizer_can_create_view_and_reply_to_own_support_ticket(): void
    {
        $data = $this->eventForOrganizer();

        $response = $this->actingAs($data['owner'])->post(route('organizer.support-tickets.store'), [
            'type' => MarketplaceSupportTicket::TYPE_EVENT,
            'priority' => MarketplaceSupportTicket::PRIORITY_HIGH,
            'subject' => 'Need help with event approval',
            'description' => 'Please check my event approval issue.',
            'event_id' => $data['event']->id,
        ]);

        $ticket = MarketplaceSupportTicket::first();
        $response->assertRedirect(route('organizer.support-tickets.show', $ticket));

        $this->actingAs($data['owner'])
            ->get(route('organizer.support-tickets.show', $ticket))
            ->assertOk()
            ->assertSee('Need help with event approval');

        $this->actingAs($data['owner'])
            ->post(route('organizer.support-tickets.reply', $ticket), ['body' => 'Organizer reply body.'])
            ->assertRedirect(route('organizer.support-tickets.show', $ticket));

        $this->assertDatabaseHas('marketplace_support_messages', [
            'marketplace_support_ticket_id' => $ticket->id,
            'body' => 'Organizer reply body.',
        ]);
    }

    public function test_organizer_cannot_view_another_organizers_ticket(): void
    {
        $ownerData = $this->eventForOrganizer('owner');
        $otherData = $this->eventForOrganizer('other');

        $ticket = MarketplaceSupportTicket::create([
            'organizer_profile_id' => $ownerData['organizer']->id,
            'created_by_type' => User::class,
            'created_by_id' => $ownerData['owner']->id,
            'type' => MarketplaceSupportTicket::TYPE_GENERAL,
            'priority' => MarketplaceSupportTicket::PRIORITY_NORMAL,
            'status' => MarketplaceSupportTicket::STATUS_OPEN,
            'subject' => 'Private organizer support ticket',
            'description' => 'Only one organizer can view this.',
        ]);

        $this->actingAs($otherData['owner'])
            ->get(route('organizer.support-tickets.show', $ticket))
            ->assertNotFound();
    }

    public function test_admin_can_view_filter_reply_and_change_ticket_status(): void
    {
        $admin = $this->admin();
        $customer = User::factory()->create();
        $ticket = $this->customerTicket($customer);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.support-tickets.index', ['status' => MarketplaceSupportTicket::STATUS_OPEN, 'priority' => MarketplaceSupportTicket::PRIORITY_NORMAL, 'type' => MarketplaceSupportTicket::TYPE_GENERAL, 'user_id' => $customer->id]))
            ->assertOk()
            ->assertSee($ticket->ticket_number);

        $this->actingAs($admin, 'admin')
            ->post(route('admin.support-tickets.reply', $ticket), [
                'body' => 'Admin reply for customer.',
                'status' => MarketplaceSupportTicket::STATUS_WAITING_CUSTOMER,
                'priority' => MarketplaceSupportTicket::PRIORITY_HIGH,
            ])
            ->assertRedirect(route('admin.support-tickets.show', $ticket));

        $this->assertDatabaseHas('marketplace_support_tickets', [
            'id' => $ticket->id,
            'status' => MarketplaceSupportTicket::STATUS_WAITING_CUSTOMER,
            'priority' => MarketplaceSupportTicket::PRIORITY_HIGH,
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'admin.support_ticket.replied',
            'auditable_type' => MarketplaceSupportTicket::class,
            'auditable_id' => $ticket->id,
        ]);

        $this->assertDatabaseHas('notifications', [
            'notifiable_type' => User::class,
            'notifiable_id' => $customer->id,
            'type' => SupportTicketUpdateNotification::class,
        ]);
    }

    public function test_admin_can_flag_and_unflag_event_or_organizer(): void
    {
        $admin = $this->admin();
        $data = $this->eventForOrganizer('flag');

        $this->actingAs($admin, 'admin')
            ->post(route('admin.moderation-flags.events.flag', $data['event']), [
                'reason' => 'Event content needs review.',
                'admin_note' => 'Internal review note.',
            ])
            ->assertRedirect(route('admin.moderation-flags.index'));

        $eventFlag = MarketplaceModerationFlag::where('flaggable_type', Event::class)->firstOrFail();

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'admin.moderation.event.flagged',
            'auditable_type' => MarketplaceModerationFlag::class,
            'auditable_id' => $eventFlag->id,
        ]);

        $this->actingAs($admin, 'admin')
            ->post(route('admin.moderation-flags.unflag', $eventFlag), ['admin_note' => 'No issue found.'])
            ->assertRedirect(route('admin.moderation-flags.index'));

        $this->assertDatabaseHas('marketplace_moderation_flags', [
            'id' => $eventFlag->id,
            'status' => MarketplaceModerationFlag::STATUS_DISMISSED,
        ]);

        $this->actingAs($admin, 'admin')
            ->post(route('admin.moderation-flags.organizers.flag', $data['organizer']), [
                'reason' => 'Organizer profile needs review.',
            ])
            ->assertRedirect(route('admin.moderation-flags.index'));

        $this->assertDatabaseHas('marketplace_moderation_flags', [
            'flaggable_type' => OrganizerProfile::class,
            'flaggable_id' => $data['organizer']->id,
            'status' => MarketplaceModerationFlag::STATUS_ACTIVE,
        ]);
    }

    public function test_guest_cannot_view_admin_support_or_moderation_pages(): void
    {
        $this->get(route('admin.support-tickets.index'))->assertRedirect(route('login'));
        $this->get(route('admin.moderation-flags.index'))->assertRedirect(route('login'));
    }

    public function test_route_safety_still_avoids_old_architecture(): void
    {
        $this->assertSame(0, Artisan::call('route:list'));

        $routeOutput = collect(app('router')->getRoutes())->map(fn ($route) => $route->getActionName())->implode(' ');

        foreach (['TemporaryBooking', 'BookingController', 'EventSeatController', 'EventSeatTypeController', 'ClearExpiredTemporaryBookings'] as $blocked) {
            $this->assertStringNotContainsString($blocked, $routeOutput);
        }
    }

    private function admin(): Admin
    {
        return Admin::create([
            'name' => 'Support Admin',
            'email' => 'support-admin-' . uniqid() . '@example.com',
            'password' => bcrypt('password'),
        ]);
    }

    private function eventForOrganizer(string $suffix = 'default'): array
    {
        $owner = User::factory()->create(['email' => 'support-owner-' . $suffix . '-' . uniqid() . '@example.com']);

        $organizer = OrganizerProfile::create([
            'user_id' => $owner->id,
            'organization_name' => 'Support Organizer ' . $suffix . ' ' . uniqid(),
            'slug' => 'support-organizer-' . $suffix . '-' . uniqid(),
            'status' => OrganizerProfile::STATUS_APPROVED,
            'approved_at' => now(),
        ]);

        $event = Event::create([
            'organizer_profile_id' => $organizer->id,
            'name' => 'Support Event ' . $suffix . ' ' . uniqid(),
            'slug' => 'support-event-' . $suffix . '-' . uniqid(),
            'status' => Event::STATUS_PUBLISHED,
            'start_date' => now()->addDays(7)->toDateString(),
            'end_date' => now()->addDays(7)->toDateString(),
            'venue' => 'Support Venue',
        ]);

        return compact('owner', 'organizer', 'event');
    }

    private function orderFor(User $customer, Event $event): Order
    {
        return Order::create([
            'user_id' => $customer->id,
            'event_id' => $event->id,
            'order_number' => 'ORD-SUP-' . strtoupper(substr(uniqid(), -8)),
            'subtotal' => 100,
            'discount_total' => 0,
            'fee_total' => 0,
            'total' => 100,
            'currency' => 'BDT',
            'status' => Order::STATUS_COMPLETED,
            'payment_status' => Order::PAYMENT_PAID,
            'customer_name' => $customer->name,
            'customer_email' => $customer->email,
        ]);
    }

    private function customerTicket(User $customer): MarketplaceSupportTicket
    {
        return MarketplaceSupportTicket::create([
            'user_id' => $customer->id,
            'created_by_type' => User::class,
            'created_by_id' => $customer->id,
            'type' => MarketplaceSupportTicket::TYPE_GENERAL,
            'priority' => MarketplaceSupportTicket::PRIORITY_NORMAL,
            'status' => MarketplaceSupportTicket::STATUS_OPEN,
            'subject' => 'Customer support question',
            'description' => 'Customer needs help.',
            'last_replied_at' => now(),
        ]);
    }
}
