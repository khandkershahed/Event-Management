@extends('organizer.layouts.app', ['title' => 'Ticket Types'])
@section('content')
<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
        <div>
            <h3>Ticket Types</h3>
            <p style="color:#6b7280;margin:0">Event: {{ $event->name }}</p>
        </div>
        <div>
            <a class="btn btn-light" href="{{ route('organizer.events.show', $event) }}">Back to Event</a>
            <a class="btn btn-primary" href="{{ route('organizer.events.ticket-types.create', $event) }}">Create Ticket Type</a>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Remaining</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($tickets as $ticket)
            @php($summary = $availability->summary($ticket))
            <tr>
                <td>{{ $ticket->name }}</td>
                <td>{{ ucwords(str_replace('_', ' ', $ticket->ticket_type ?? 'paid')) }}</td>
                <td>{{ $ticket->currency ?? 'BDT' }} {{ number_format((float) $ticket->price, 2) }}</td>
                <td>{{ $ticket->quantity }}</td>
                <td>{{ $summary['remaining_quantity'] }}</td>
                <td>{{ ucwords(str_replace('_', ' ', $ticket->status ?? 'active')) }}</td>
                <td>
                    <a class="btn btn-light" href="{{ route('organizer.events.ticket-types.show', [$event, $ticket]) }}">View</a>
                    <a class="btn btn-primary" href="{{ route('organizer.events.ticket-types.edit', [$event, $ticket]) }}">Edit</a>
                </td>
            </tr>
        @empty
            <tr><td colspan="7">No ticket types found for this event.</td></tr>
        @endforelse
        </tbody>
    </table>

    {{ $tickets->links() }}
</div>
@endsection
