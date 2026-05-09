@extends('organizer.layouts.app')

@section('content')
<div class="card">
    <div style="display:flex;justify-content:space-between;gap:12px;align-items:center;flex-wrap:wrap">
        <div>
            <h2 style="margin:0">Event Sales Report</h2>
            <p style="margin:6px 0 0;color:#6b7280">{{ $event->name }}</p>
        </div>
        <div>
            <a href="{{ route('organizer.events.attendees.index', $event) }}" class="btn btn-primary">View Attendees</a>
            <a href="{{ route('organizer.events.attendees.export', $event) }}" class="btn btn-light">Export CSV</a>
        </div>
    </div>
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
    <h3 style="margin-top:0">Sales by Ticket Type</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Ticket Type</th>
                <th>Paid Tickets Sold</th>
                <th>Paid Gross Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ticketRows as $row)
                <tr>
                    <td>{{ $row->ticket_name ?: 'Ticket' }}</td>
                    <td>{{ (int) $row->tickets_sold }}</td>
                    <td>{{ number_format((float) $row->gross_amount, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="3">No paid ticket sales found yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="card">
    <h3 style="margin-top:0">Recent Orders</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Order</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Status</th>
                <th>Payment</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>{{ $order->order_number }}</td>
                    <td>{{ $order->customer_name ?: optional($order->user)->name ?: 'Guest' }}</td>
                    <td>{{ $order->currency }} {{ number_format((float) $order->total, 2) }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $order->status)) }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}</td>
                </tr>
            @empty
                <tr><td colspan="5">No orders found.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:16px">
        {{ $orders->links() }}
    </div>
</div>
@endsection
