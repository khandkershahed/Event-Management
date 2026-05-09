@extends('organizer.layouts.app')

@section('content')
<div class="card">
    <div style="display:flex;justify-content:space-between;gap:12px;align-items:center;flex-wrap:wrap">
        <div>
            <h2 style="margin:0">Attendees</h2>
            <p style="margin:6px 0 0;color:#6b7280">{{ $event->name }}</p>
        </div>
        <div>
            <a href="{{ route('organizer.reports.events.show', $event) }}" class="btn btn-light">Sales Report</a>
            <a href="{{ route('organizer.events.attendees.export', $event) }}" class="btn btn-primary">Export CSV</a>
        </div>
    </div>
</div>

<div class="grid">
    <div class="card"><strong>{{ $summary['issued_tickets'] }}</strong><br><span style="color:#6b7280">Issued Tickets</span></div>
    <div class="card"><strong>{{ $summary['checked_in'] }}</strong><br><span style="color:#6b7280">Checked In</span></div>
    <div class="card"><strong>{{ $summary['cancelled'] }}</strong><br><span style="color:#6b7280">Cancelled</span></div>
    <div class="card"><strong>{{ $summary['refunded'] }}</strong><br><span style="color:#6b7280">Refunded</span></div>
</div>

<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>Attendee</th>
                <th>Ticket</th>
                <th>Seat</th>
                <th>Status</th>
                <th>Check-In</th>
                <th>Checked-In At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendees as $ticket)
                <tr>
                    <td>
                        <strong>{{ $ticket->attendee_name ?: optional($ticket->order)->customer_name ?: 'Guest Attendee' }}</strong><br>
                        <small>{{ $ticket->attendee_email ?: optional($ticket->order)->customer_email ?: 'No email' }}</small>
                    </td>
                    <td>
                        <strong>{{ $ticket->ticket_code }}</strong><br>
                        <small>{{ optional($ticket->eventTicket)->name ?: optional($ticket->orderItem)->ticket_name ?: 'Ticket' }}</small>
                    </td>
                    <td>{{ optional($ticket->seat)->label ?: optional($ticket->seat)->seat_number ?: '-' }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</td>
                    <td>{{ $ticket->is_checked_in ? 'Checked In' : 'Not Checked In' }}</td>
                    <td>{{ optional($ticket->checked_in_at)->format('Y-m-d H:i') ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No attendees found for this event yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:16px">
        {{ $attendees->links() }}
    </div>
</div>
@endsection
