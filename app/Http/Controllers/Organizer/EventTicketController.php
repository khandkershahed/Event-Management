<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventTicketStoreRequest;
use App\Http\Requests\EventTicketUpdateRequest;
use App\Models\Event;
use App\Models\EventTicket;
use App\Models\SeatingSection;
use App\Services\TicketAvailabilityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventTicketController extends Controller
{
    public function index(Request $request, Event $event, TicketAvailabilityService $availability): View
    {
        $this->ensureOwnEvent($request, $event);

        $tickets = $event->tickets()->latest()->paginate(10);

        return view('organizer.event-tickets.index', compact('event', 'tickets', 'availability'));
    }

    public function create(Request $request, Event $event): View
    {
        $this->ensureOwnEvent($request, $event);
        $this->ensureTicketSetupAllowed($event);

        $ticket = new EventTicket([
            'ticket_type' => EventTicket::TYPE_PAID,
            'currency' => 'BDT',
            'quantity' => 100,
            'sold_quantity' => 0,
            'min_per_order' => 1,
            'visibility' => EventTicket::VISIBILITY_PUBLIC,
            'status' => EventTicket::STATUS_ACTIVE,
            'platform_fee_type' => EventTicket::FEE_NONE,
            'platform_fee_value' => 0,
            'organizer_absorbs_fee' => false,
        ]);

        $sections = $this->sectionsForEvent($event);

        return view('organizer.event-tickets.create', compact('event', 'ticket', 'sections'));
    }

    public function store(EventTicketStoreRequest $request, Event $event): RedirectResponse
    {
        $this->ensureOwnEvent($request, $event);
        $this->ensureTicketSetupAllowed($event);

        $ticket = $event->tickets()->create($this->payload($request));

        return redirect()->route('organizer.events.ticket-types.show', [$event, $ticket])
            ->with('success', 'Ticket type created successfully.');
    }

    public function show(Request $request, Event $event, EventTicket $ticket, TicketAvailabilityService $availability): View
    {
        $this->ensureOwnEvent($request, $event);
        $this->ensureOwnTicket($event, $ticket);

        $sections = $this->sectionsForEvent($event);
        $availabilitySummary = $availability->summary($ticket);

        return view('organizer.event-tickets.show', compact('event', 'ticket', 'sections', 'availabilitySummary'));
    }

    public function edit(Request $request, Event $event, EventTicket $ticket): View
    {
        $this->ensureOwnEvent($request, $event);
        $this->ensureOwnTicket($event, $ticket);
        $this->ensureTicketSetupAllowed($event);

        $sections = $this->sectionsForEvent($event);

        return view('organizer.event-tickets.edit', compact('event', 'ticket', 'sections'));
    }

    public function update(EventTicketUpdateRequest $request, Event $event, EventTicket $ticket): RedirectResponse
    {
        $this->ensureOwnEvent($request, $event);
        $this->ensureOwnTicket($event, $ticket);
        $this->ensureTicketSetupAllowed($event);

        $payload = $this->payload($request);

        if (! $ticket->canBeEditedSafely()) {
            unset(
                $payload['ticket_type'],
                $payload['price'],
                $payload['currency'],
                $payload['quantity'],
                $payload['valid_section_ids'],
                $payload['platform_fee_type'],
                $payload['platform_fee_value'],
                $payload['organizer_absorbs_fee']
            );
        }

        $ticket->update($payload);

        return redirect()->route('organizer.events.ticket-types.show', [$event, $ticket])
            ->with('success', 'Ticket type updated successfully.');
    }

    public function destroy(Request $request, Event $event, EventTicket $ticket): RedirectResponse
    {
        $this->ensureOwnEvent($request, $event);
        $this->ensureOwnTicket($event, $ticket);

        if (! $ticket->canBeEditedSafely()) {
            $ticket->update([
                'status' => EventTicket::STATUS_ARCHIVED,
                'is_active' => false,
            ]);

            return redirect()->route('organizer.events.ticket-types.index', $event)
                ->with('success', 'Ticket type has sales, so it was archived instead of deleted.');
        }

        $ticket->delete();

        return redirect()->route('organizer.events.ticket-types.index', $event)
            ->with('success', 'Ticket type deleted successfully.');
    }

    private function payload(Request $request): array
    {
        $status = $request->input('status', EventTicket::STATUS_ACTIVE);
        $ticketType = $request->input('ticket_type', EventTicket::TYPE_PAID);
        $price = $ticketType === EventTicket::TYPE_FREE ? 0 : (float) $request->input('price', 0);

        return [
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'ticket_type' => $ticketType,
            'price' => $price,
            'currency' => strtoupper($request->input('currency', 'BDT')),
            'quantity' => (int) $request->input('quantity'),
            'min_per_order' => (int) $request->input('min_per_order', 1),
            'max_per_order' => $request->filled('max_per_order') ? (int) $request->input('max_per_order') : null,
            'sales_start_at' => $request->input('sales_start_at'),
            'sales_end_at' => $request->input('sales_end_at'),
            'visibility' => $request->input('visibility', EventTicket::VISIBILITY_PUBLIC),
            'status' => $status,
            'is_active' => $status === EventTicket::STATUS_ACTIVE,
            'valid_section_ids' => collect($request->input('valid_section_ids', []))->filter()->map(fn ($id) => (int) $id)->values()->all(),
            'platform_fee_type' => $request->input('platform_fee_type', EventTicket::FEE_NONE),
            'platform_fee_value' => (float) $request->input('platform_fee_value', 0),
            'organizer_absorbs_fee' => $request->boolean('organizer_absorbs_fee'),
        ];
    }

    private function ensureOwnEvent(Request $request, Event $event): void
    {
        $profile = $request->user()->organizerProfile;

        abort_unless($profile && (int) $event->organizer_profile_id === (int) $profile->id, 403);
    }

    private function ensureOwnTicket(Event $event, EventTicket $ticket): void
    {
        abort_unless((int) $ticket->event_id === (int) $event->id, 403);
    }

    private function ensureTicketSetupAllowed(Event $event): void
    {
        abort_if(in_array($event->status, [Event::STATUS_CANCELLED, Event::STATUS_COMPLETED], true), 403, 'Ticket setup is closed for this event.');
    }

    private function sectionsForEvent(Event $event)
    {
        if (! $event->seating_plan_id) {
            return collect();
        }

        return SeatingSection::where('seating_plan_id', $event->seating_plan_id)
            ->orderBy('name')
            ->get();
    }
}
