<?php

namespace App\Services\EventManagement;

use App\Models\Event;
use App\Models\Order;
use App\Models\OrderTicket;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Route;

class EventControlPanelService
{
    public function build(Event $event, string $area): array
    {
        $event->loadMissing(['eventType', 'venueModel', 'seatingPlan.sections.seats', 'tickets', 'orders', 'orderTickets', 'refundRequests', 'cancellationRequests']);

        $orders = $event->orders;
        $tickets = $event->tickets;
        $orderTickets = $event->orderTickets;
        $paidOrders = $orders->filter(fn ($order) => in_array((string) ($order->payment_status ?? $order->status ?? ''), ['paid', 'completed'], true));
        $issuedTickets = $orderTickets->filter(fn ($ticket) => in_array((string) ($ticket->status ?? ''), ['issued', 'used'], true));
        $checkedInTickets = $orderTickets->filter(fn ($ticket) => filled($ticket->checked_in_at ?? null) || (string) ($ticket->status ?? '') === 'used');

        return [
            'event' => $event,
            'area' => $area,
            'summary' => [
                'status' => ucwords(str_replace('_', ' ', (string) $event->status)),
                'venue' => $event->venueModel?->name ?: $event->venue ?: 'Missing venue',
                'seating_plan' => $event->seatingPlan?->name ?: 'General admission / no seating plan',
                'tickets_count' => $tickets->count(),
                'orders_count' => $orders->count(),
                'paid_orders_count' => $paidOrders->count(),
                'issued_tickets_count' => $issuedTickets->count(),
                'checked_in_count' => $checkedInTickets->count(),
                'gross_sales' => $this->money($paidOrders->sum(fn ($order) => (float) ($order->total_amount ?? $order->grand_total ?? $order->amount ?? 0))),
                'refunds_count' => $event->refundRequests->count(),
                'cancellations_count' => $event->cancellationRequests->count(),
            ],
            'checklist' => $this->checklist($event),
            'warnings' => $this->warnings($event),
            'actions' => $this->actions($event, $area),
        ];
    }

    private function checklist(Event $event): array
    {
        return [
            ['label' => 'Basic event details', 'done' => filled($event->name) && filled($event->description)],
            ['label' => 'Event date and time', 'done' => filled($event->start_date) && filled($event->end_date)],
            ['label' => 'Main event media', 'done' => filled($event->image) || filled($event->banner_image) || filled($event->logo)],
            ['label' => 'Venue selected', 'done' => filled($event->venue_id) || filled($event->venue)],
            ['label' => 'Seating plan ready when needed', 'done' => ! $event->seating_plan_id || (bool) $event->seatingPlan?->design_json],
            ['label' => 'Ticket types created', 'done' => $event->tickets->count() > 0],
            ['label' => 'Ticket-section assignment checked', 'done' => ! $event->seating_plan_id || $event->tickets->contains(fn ($ticket) => ! empty($ticket->valid_section_ids))],
            ['label' => 'Ready for public sales', 'done' => in_array($event->status, [Event::STATUS_APPROVED, Event::STATUS_PUBLISHED], true)],
        ];
    }

    private function warnings(Event $event): array
    {
        $warnings = [];
        if (blank($event->description)) { $warnings[] = 'Add a clear event description before publishing.'; }
        if (blank($event->start_date)) { $warnings[] = 'Select the event start date.'; }
        if (blank($event->venue_id) && blank($event->venue)) { $warnings[] = 'Select or create a venue.'; }
        if ($event->seating_plan_id && blank($event->seatingPlan?->design_json)) { $warnings[] = 'Open the seat map designer and save the seating layout.'; }
        if ($event->tickets->isEmpty()) { $warnings[] = 'Create at least one ticket type.'; }
        return $warnings;
    }

    private function actions(Event $event, string $area): array
    {
        if ($area === 'admin') {
            return [
                ['label' => 'Edit Event Details', 'url' => route('admin.event.edit', $event), 'primary' => true],
                ['label' => 'Manage Ticket Types', 'url' => route('admin.events.ticket-types.index', $event), 'primary' => true],
                ['label' => 'Assign Tickets to Sections', 'url' => route('admin.events.ticket-types.manage', $event)],
                ['label' => 'Open Seat Map Designer', 'url' => $event->seating_plan_id ? route('admin.seating-plans.designer', $event->seating_plan_id) : null],
                ['label' => 'Edit Venue', 'url' => $event->venue_id ? route('admin.venue.edit', $event->venue_id) : route('admin.venue.create')],
                ['label' => 'Edit Seating Plan', 'url' => $event->seating_plan_id ? route('admin.seating-plans.edit', $event->seating_plan_id) : route('admin.seating-plans.create')],
                ['label' => 'Approval Screen', 'url' => route('admin.event-approvals.show', $event)],
                ['label' => 'Sales Reports', 'url' => route('admin.marketplace-reports.sales-by-event')],
            ];
        }

        return [
            ['label' => 'Edit Event Details', 'url' => route('organizer.events.edit', $event), 'primary' => true],
            ['label' => 'Manage Ticket Types', 'url' => route('organizer.events.ticket-types.index', $event), 'primary' => true],
            ['label' => 'Assign Tickets to Sections', 'url' => route('organizer.events.ticket-types.index', $event)],
            ['label' => 'Open Seat Map Designer', 'url' => $event->seating_plan_id ? route('organizer.seating-plans.designer', $event->seating_plan_id) : null],
            ['label' => 'Select/Create Venue', 'url' => $event->venue_id ? route('organizer.venues.edit', $event->venue_id) : route('organizer.venues.create')],
            ['label' => 'Select/Create Seating Plan', 'url' => $event->seating_plan_id ? route('organizer.seating-plans.edit', $event->seating_plan_id) : route('organizer.seating-plans.create')],
            ['label' => 'View Orders', 'url' => route('organizer.orders.index')],
            ['label' => 'View Attendees', 'url' => route('organizer.events.attendees.index', $event)],
            ['label' => 'Open Check-in', 'url' => route('organizer.check-in.index')],
            ['label' => 'View Sales Report', 'url' => route('organizer.reports.events.show', $event)],
        ];
    }

    private function money(float $amount): string
    {
        return number_format($amount, 2);
    }
}
