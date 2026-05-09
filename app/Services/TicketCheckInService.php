<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Order;
use App\Models\OrderTicket;
use App\Models\OrganizerProfile;
use App\Models\TicketCheckIn;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketCheckInService
{
    public function checkIn(string $code, Event $event, OrganizerProfile $organizer, ?User $checkedBy = null, ?Request $request = null): array
    {
        $code = trim($code);

        if ($event->organizer_profile_id !== $organizer->id) {
            return $this->record(null, $event, $checkedBy, TicketCheckIn::RESULT_WRONG_EVENT, $code, 'This event does not belong to your organizer profile.', $request);
        }

        $ticket = OrderTicket::query()->with(['order', 'event', 'eventTicket', 'seat'])
            ->where(fn ($query) => $query->where('ticket_code', $code)->orWhere('qr_payload', $code))
            ->first();

        if (! $ticket) {
            return $this->record(null, $event, $checkedBy, TicketCheckIn::RESULT_NOT_FOUND, $code, 'No ticket was found for this code.', $request);
        }

        $ticketEventBelongsToOrganizer = (int) ($ticket->event?->organizer_profile_id ?? 0) === (int) $organizer->id;

        if (! $ticketEventBelongsToOrganizer) {
            return $this->record($ticket, $event, $checkedBy, TicketCheckIn::RESULT_WRONG_EVENT, $code, 'Ticket belongs to a different event.', $request);
        }

        if ($ticket->status === OrderTicket::STATUS_CANCELLED) {
            return $this->record($ticket, $event, $checkedBy, TicketCheckIn::RESULT_CANCELLED, $code, 'This ticket is cancelled.', $request);
        }

        if ($ticket->status === OrderTicket::STATUS_REFUNDED) {
            return $this->record($ticket, $event, $checkedBy, TicketCheckIn::RESULT_REFUNDED, $code, 'This ticket is refunded.', $request);
        }

        if ((int) $ticket->event_id !== (int) $event->id) {
            return $this->record($ticket, $event, $checkedBy, TicketCheckIn::RESULT_WRONG_EVENT, $code, 'Ticket belongs to a different event.', $request);
        }

        if ($ticket->status === OrderTicket::STATUS_USED || (bool) $ticket->is_checked_in) {
            return $this->record($ticket, $event, $checkedBy, TicketCheckIn::RESULT_ALREADY_CHECKED_IN, $code, 'This ticket has already been checked in.', $request);
        }

        if (! $ticket->order || $ticket->order->payment_status !== Order::PAYMENT_PAID || ! in_array($ticket->order->status, [Order::STATUS_COMPLETED, Order::STATUS_PAID], true)) {
            return $this->record($ticket, $event, $checkedBy, TicketCheckIn::RESULT_CANCELLED, $code, 'This ticket order is not paid or completed.', $request);
        }

        return DB::transaction(function () use ($ticket, $event, $checkedBy, $code, $request): array {
            $lockedTicket = OrderTicket::query()->lockForUpdate()->find($ticket->id);

            if (! $lockedTicket || $lockedTicket->status === OrderTicket::STATUS_USED || (bool) $lockedTicket->is_checked_in) {
                return $this->record($ticket, $event, $checkedBy, TicketCheckIn::RESULT_ALREADY_CHECKED_IN, $code, 'This ticket has already been checked in.', $request);
            }

            $checkedAt = now();
            $lockedTicket->forceFill([
                'status' => OrderTicket::STATUS_USED,
                'is_checked_in' => true,
                'checked_in_at' => $checkedAt,
            ])->save();

            return $this->record($lockedTicket->fresh(['order', 'event', 'eventTicket', 'seat']), $event, $checkedBy, TicketCheckIn::RESULT_VALID, $code, 'Check-in successful.', $request, $checkedAt);
        });
    }

    protected function record(?OrderTicket $ticket, Event $event, ?User $checkedBy, string $result, string $code, string $message, ?Request $request = null, $checkedAt = null): array
    {
        $checkedAt = $checkedAt ?: now();
        $checkIn = TicketCheckIn::create([
            'order_ticket_id' => $ticket?->id,
            'event_id' => $event->id,
            'checked_in_by' => $checkedBy?->id,
            'checked_in_at' => $checkedAt,
            'result' => $result,
            'scanned_code' => $code,
            'notes' => $message,
            'ip_address' => $request?->ip(),
            'session_id' => $request?->session()?->getId(),
            'user_agent' => $request?->userAgent(),
        ]);

        app(AuditLogService::class)->record(
            'organizer.ticket_check_in.' . $result,
            $checkIn,
            [],
            [
                'event_id' => $event->id,
                'order_ticket_id' => $ticket?->id,
                'result' => $result,
                'checked_in_by' => $checkedBy?->id,
            ],
            'Ticket check-in result: ' . $result,
            $checkedBy,
            $checkedBy ? 'web' : null,
            $request
        );

        return ['result' => $result, 'message' => $message, 'ticket' => $ticket, 'event' => $event];
    }
}
