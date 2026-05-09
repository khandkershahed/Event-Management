@extends('organizer.layouts.app', ['title' => 'Support Tickets'])

@section('content')
<div class="card" style="display:flex;justify-content:space-between;align-items:center;flex-direction:row">
    <div>
        <h2>Support Tickets</h2>
        <p>Track organizer support, payout, event approval, and marketplace issues.</p>
    </div>
    <a href="{{ route('organizer.support-tickets.create') }}" class="btn btn-primary">Create Ticket</a>
</div>

<div class="card">
    <table class="table">
        <thead><tr><th>No.</th><th>Subject</th><th>Type</th><th>Status</th><th>Priority</th><th>Last Reply</th><th>Action</th></tr></thead>
        <tbody>
            @forelse($tickets as $ticket)
                <tr>
                    <td>{{ $ticket->ticket_number }}</td>
                    <td>{{ $ticket->subject }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $ticket->type)) }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</td>
                    <td>{{ ucfirst($ticket->priority) }}</td>
                    <td>{{ optional($ticket->last_replied_at)->format('d M Y, h:i A') }}</td>
                    <td><a href="{{ route('organizer.support-tickets.show', $ticket) }}" class="btn btn-primary">View</a></td>
                </tr>
            @empty
                <tr><td colspan="7" style="text-align:center;color:#6b7280;padding:24px">No support tickets found.</td></tr>
            @endforelse
        </tbody>
    </table>
    {{ $tickets->links() }}
</div>
@endsection
