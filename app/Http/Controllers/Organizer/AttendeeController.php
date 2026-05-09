<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\OrderTicket;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AttendeeController extends Controller
{
    public function index(Request $request, Event $event): View
    {
        $profile = $request->user()->organizerProfile;
        $this->ensureOwnEvent($event, $profile?->id);

        $attendees = $this->attendeeQuery($event)
            ->paginate(25)
            ->withQueryString();

        return view('organizer.attendees.index', [
            'event' => $event,
            'attendees' => $attendees,
            'summary' => $this->summary($event),
        ]);
    }

    public function export(Request $request, Event $event): StreamedResponse
    {
        $profile = $request->user()->organizerProfile;
        $this->ensureOwnEvent($event, $profile?->id);

        $fileName = 'attendees-' . ($event->slug ?: $event->id) . '.csv';

        return response()->streamDownload(function () use ($event) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Order Number',
                'Attendee Name',
                'Attendee Email',
                'Ticket Code',
                'Ticket Type',
                'Seat',
                'Ticket Status',
                'Check-In Status',
                'Checked-In At',
            ]);

            $this->attendeeQuery($event)->chunk(200, function ($tickets) use ($handle) {
                foreach ($tickets as $ticket) {
                    fputcsv($handle, [
                        optional($ticket->order)->order_number,
                        $ticket->attendee_name ?: optional($ticket->order)->customer_name,
                        $ticket->attendee_email ?: optional($ticket->order)->customer_email,
                        $ticket->ticket_code,
                        optional($ticket->eventTicket)->name ?: optional($ticket->orderItem)->ticket_name,
                        optional($ticket->seat)->label ?: optional($ticket->seat)->seat_number,
                        $ticket->status,
                        $ticket->is_checked_in ? 'Checked In' : 'Not Checked In',
                        optional($ticket->checked_in_at)->format('Y-m-d H:i:s'),
                    ]);
                }
            });

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function attendeeQuery(Event $event)
    {
        return OrderTicket::query()
            ->with(['order', 'orderItem', 'eventTicket', 'seat'])
            ->where('event_id', $event->id)
            ->latest('id');
    }

    private function summary(Event $event): array
    {
        $query = OrderTicket::query()->where('event_id', $event->id);

        return [
            'issued_tickets' => (clone $query)->count(),
            'checked_in' => (clone $query)->where('is_checked_in', true)->count(),
            'cancelled' => (clone $query)->where('status', OrderTicket::STATUS_CANCELLED)->count(),
            'refunded' => (clone $query)->where('status', OrderTicket::STATUS_REFUNDED)->count(),
        ];
    }

    private function ensureOwnEvent(Event $event, ?int $organizerProfileId): void
    {
        abort_if(! $organizerProfileId || (int) $event->organizer_profile_id !== (int) $organizerProfileId, Response::HTTP_NOT_FOUND);
    }
}
