@extends('organizer.layouts.app', ['title' => 'Ticket Check-In'])

@section('content')
<div class="card">
    <h3>Ticket Check-In</h3>
    <p style="color:#6b7280;margin-bottom:18px">Enter a ticket code or paste the QR payload text from the customer's ticket. Step 13 supports owner-organizer check-in only. Team/staff access is planned for a later step.</p>

    <form method="POST" action="{{ route('organizer.check-in.validate') }}">
        @csrf
        <div class="mb-3">
            <label for="event_id">Event</label>
            <select id="event_id" name="event_id" class="form-control" required>
                <option value="">Select event</option>
                @foreach($events as $event)
                    <option value="{{ $event->id }}" @selected(old('event_id', optional($selectedEvent)->id) == $event->id)>{{ $event->name }} — {{ $event->start_date ? $event->start_date->format('Y-m-d') : 'No date' }}</option>
                @endforeach
            </select>
            @error('event_id')<div style="color:#dc2626;margin-top:6px">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="ticket_code">Ticket Code / QR Payload</label>
            <textarea id="ticket_code" name="ticket_code" class="form-control" rows="3" required>{{ old('ticket_code') }}</textarea>
            @error('ticket_code')<div style="color:#dc2626;margin-top:6px">{{ $message }}</div>@enderror
        </div>

        <button type="submit" class="btn btn-primary">Validate Ticket</button>
    </form>
</div>

@if($checkInResult)
    @php
        $resultColor = $checkInResult['result'] === \App\Models\TicketCheckIn::RESULT_VALID ? '#16a34a' : '#dc2626';
        $ticket = $checkInResult['ticket'] ?? null;
    @endphp
    <div class="card" style="border-left:4px solid {{ $resultColor }}">
        <h4>{{ ucwords(str_replace('_', ' ', $checkInResult['result'])) }}</h4>
        <p>{{ $checkInResult['message'] }}</p>

        @if($ticket)
            <table class="table">
                <tr><th>Ticket Code</th><td>{{ $ticket->ticket_code }}</td></tr>
                <tr><th>Event</th><td>{{ optional($ticket->event)->name }}</td></tr>
                <tr><th>Ticket Type</th><td>{{ optional($ticket->eventTicket)->name ?? optional($ticket->ticketType)->name ?? '-' }}</td></tr>
                <tr><th>Seat</th><td>{{ optional($ticket->seat)->label ?? '-' }}</td></tr>
                <tr><th>Status</th><td>{{ ucwords(str_replace('_', ' ', $ticket->status)) }}</td></tr>
                <tr><th>Checked In At</th><td>{{ $ticket->checked_in_at ? $ticket->checked_in_at->format('Y-m-d H:i') : '-' }}</td></tr>
            </table>
        @endif
    </div>
@endif

<div class="card">
    <h4>Recent Owner Events</h4>
    <table class="table">
        <thead><tr><th>Event</th><th>Date</th><th>Status</th></tr></thead>
        <tbody>
        @forelse($events as $event)
            <tr>
                <td>{{ $event->name }}</td>
                <td>{{ $event->start_date ? $event->start_date->format('Y-m-d') : '-' }}</td>
                <td>{{ ucwords(str_replace('_', ' ', $event->status)) }}</td>
            </tr>
        @empty
            <tr><td colspan="3">No published or completed events are available for check-in.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
