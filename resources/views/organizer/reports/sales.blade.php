@extends('organizer.layouts.app')

@section('content')
<div class="card">
    <h2 style="margin:0">Sales Reports</h2>
    <p style="margin:6px 0 0;color:#6b7280">Organizer-wide sales overview from current orders, tickets, payments, and check-ins.</p>
</div>

<div class="grid">
    <div class="card"><strong>{{ $summary['total_orders'] }}</strong><br><span style="color:#6b7280">Total Orders</span></div>
    <div class="card"><strong>{{ $summary['paid_orders'] }}</strong><br><span style="color:#6b7280">Paid Orders</span></div>
    <div class="card"><strong>{{ number_format($summary['gross_revenue'], 2) }}</strong><br><span style="color:#6b7280">Gross Revenue</span></div>
    <div class="card"><strong>{{ $summary['issued_tickets'] }}</strong><br><span style="color:#6b7280">Issued Tickets</span></div>
    <div class="card"><strong>{{ $summary['checked_in'] }}</strong><br><span style="color:#6b7280">Checked-In</span></div>
    <div class="card"><strong>{{ number_format($summary['pending_payment_amount'], 2) }}</strong><br><span style="color:#6b7280">Pending Payment</span></div>
</div>

<div class="card">
    <h3 style="margin-top:0">Events</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Event</th>
                <th>Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($events as $event)
                <tr>
                    <td>{{ $event->name }}</td>
                    <td>{{ optional($event->start_date)->format('Y-m-d') ?: $event->start_date }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $event->status)) }}</td>
                    <td>
                        <a href="{{ route('organizer.reports.events.show', $event) }}" class="btn btn-light">Report</a>
                        <a href="{{ route('organizer.events.attendees.index', $event) }}" class="btn btn-light">Attendees</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4">No events found.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:16px">
        {{ $events->links() }}
    </div>
</div>
@endsection
