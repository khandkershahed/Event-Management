@extends('organizer.layouts.app', ['title' => 'Organizer Dashboard'])
@section('content')
@php
    $money = fn ($value) => 'BDT ' . number_format((float) $value, 2);
    $cards = [
        ['label' => 'Total Events', 'value' => number_format($stats['total_events'])],
        ['label' => 'Draft Events', 'value' => number_format($stats['draft_events'])],
        ['label' => 'Submitted Events', 'value' => number_format($stats['submitted_events'])],
        ['label' => 'Published Events', 'value' => number_format($stats['published_events'])],
        ['label' => 'Venues', 'value' => number_format($stats['venues'])],
        ['label' => 'Seating Plans', 'value' => number_format($stats['seating_plans'])],
        ['label' => 'Ticket Types', 'value' => number_format($stats['ticket_types'])],
        ['label' => 'Total Orders', 'value' => number_format($stats['total_orders'])],
        ['label' => 'Paid Orders', 'value' => number_format($stats['paid_orders'])],
        ['label' => 'Pending Payments', 'value' => number_format($stats['pending_payment_orders'])],
        ['label' => 'Revenue', 'value' => $money($stats['revenue'])],
        ['label' => 'Net Earnings', 'value' => $money($stats['net_earnings'])],
        ['label' => 'Issued Tickets', 'value' => number_format($stats['issued_tickets'])],
        ['label' => 'Checked-In Tickets', 'value' => number_format($stats['checked_in_tickets'])],
        ['label' => 'Pending Refunds', 'value' => number_format($stats['pending_refunds'])],
        ['label' => 'Support Tickets', 'value' => number_format($stats['support_tickets'])],
        ['label' => 'Pending Payouts', 'value' => number_format($stats['pending_payouts'])],
        ['label' => 'Paid Payouts', 'value' => number_format($stats['paid_payouts'])],
        ['label' => 'Ledger Balance', 'value' => $money($stats['ledger_balance'])],
    ];
    $maxSale = max(1, collect($sales_chart)->max('sales'));
@endphp

<div class="mb-4">
    <h1 class="h3 mb-1">Organizer Dashboard</h1>
    <p class="text-muted mb-0">Real-time operations for {{ $profile->organization_name }}: sales, orders, attendees, refunds, payouts, support, and event performance.</p>
</div>

<div class="grid" role="list" aria-label="Organizer dashboard real stats">
    <div class="card" role="listitem"><small>Status</small><h2>{{ ucfirst($profile->status) }}</h2></div>
    @foreach($cards as $card)
        <div class="card" role="listitem"><small>{{ $card['label'] }}</small><h2>{{ $card['value'] }}</h2></div>
    @endforeach
</div>

<div class="card mt-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <h3 class="mb-1">Marketplace Actions</h3>
            <p class="text-muted mb-0">Create events, manage ticket setup, verify attendees, review payouts, and respond to customers.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a class="btn btn-primary" href="{{ route('organizer.events.index') }}">Manage Events</a>
            <a class="btn btn-light" href="{{ route('organizer.reports.sales') }}">Sales Reports</a>
            <a class="btn btn-light" href="{{ route('organizer.check-in.index') }}">Check-In</a>
            <a class="btn btn-light" href="{{ route('organizer.payouts.index') }}">Payouts</a>
        </div>
    </div>
</div>

<div class="grid mt-4">
    <div class="card" style="grid-column:span 2">
        <h3>Last 30 Days Sales</h3>
        <div style="display:flex;align-items:end;gap:3px;height:170px" aria-label="last 30 days sales chart style block">
            @foreach($sales_chart as $day)
                <div title="{{ $day['label'] }}: {{ $money($day['sales']) }}" style="height:{{ max(5, round(($day['sales'] / $maxSale) * 160)) }}px;background:#2563eb;border-radius:4px 4px 0 0;flex:1"></div>
            @endforeach
        </div>
        <small class="text-muted">Paid order sales by day.</small>
    </div>
    <div class="card">
        <h3>Order Status Summary</h3>
        @forelse($orders_by_status as $status => $count)
            <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid #e5e7eb"><span>{{ ucfirst(str_replace('_', ' ', $status)) }}</span><strong>{{ $count }}</strong></div>
        @empty
            <p class="text-muted">No order data yet.</p>
        @endforelse
    </div>
    <div class="card">
        <h3>Payout / Refund Summary</h3>
        <div class="d-flex justify-content-between py-2"><span>Pending Payouts</span><strong>{{ $payout_refund_summary['pending_payouts'] }}</strong></div>
        <div class="d-flex justify-content-between py-2"><span>Paid Payouts</span><strong>{{ $payout_refund_summary['paid_payouts'] }}</strong></div>
        <div class="d-flex justify-content-between py-2"><span>Pending Refunds</span><strong>{{ $payout_refund_summary['pending_refunds'] }}</strong></div>
        <div class="d-flex justify-content-between py-2"><span>Approved Refunds</span><strong>{{ $payout_refund_summary['approved_refunds'] }}</strong></div>
    </div>
</div>

<div class="grid mt-4">
    <div class="card" style="grid-column:span 2">
        <h3>Recent Orders</h3>
        <div class="table-responsive"><table class="table"><thead><tr><th>Order</th><th>Event</th><th>Total</th><th>Status</th></tr></thead><tbody>
            @forelse($recent_orders as $order)
                <tr><td>{{ $order->order_number }}</td><td>{{ $order->event?->name ?? '-' }}</td><td>{{ $order->currency }} {{ number_format((float) $order->total, 2) }}</td><td>{{ ucfirst(str_replace('_', ' ', $order->status)) }}</td></tr>
            @empty <tr><td colspan="4" class="text-muted">No recent orders.</td></tr> @endforelse
        </tbody></table></div>
    </div>
    <div class="card" style="grid-column:span 2">
        <h3>Latest Events</h3>
        <div class="table-responsive"><table class="table"><thead><tr><th>Event</th><th>Date</th><th>Status</th></tr></thead><tbody>
            @forelse($latest_events as $event)
                <tr><td>{{ $event->name }}</td><td>{{ $event->start_date?->format('d M Y') ?: '-' }}</td><td>{{ ucfirst(str_replace('_', ' ', $event->status)) }}</td></tr>
            @empty <tr><td colspan="3" class="text-muted">No events yet.</td></tr> @endforelse
        </tbody></table></div>
    </div>
    <div class="card" style="grid-column:span 2">
        <h3>Recent Payouts</h3>
        <div class="table-responsive"><table class="table"><thead><tr><th>Payout</th><th>Amount</th><th>Status</th></tr></thead><tbody>
            @forelse($recent_payouts as $payout)
                <tr><td>{{ $payout->payout_number }}</td><td>{{ $payout->currency }} {{ number_format((float) $payout->amount, 2) }}</td><td>{{ ucfirst($payout->status) }}</td></tr>
            @empty <tr><td colspan="3" class="text-muted">No payouts yet.</td></tr> @endforelse
        </tbody></table></div>
    </div>
    <div class="card" style="grid-column:span 2">
        <h3>Refund Requests</h3>
        <div class="table-responsive"><table class="table"><thead><tr><th>Order</th><th>Customer</th><th>Amount</th><th>Status</th></tr></thead><tbody>
            @forelse($refund_requests as $refund)
                <tr><td>{{ $refund->order?->order_number ?? '-' }}</td><td>{{ $refund->user?->name ?? '-' }}</td><td>{{ $refund->currency }} {{ number_format((float) $refund->amount, 2) }}</td><td>{{ ucfirst($refund->status) }}</td></tr>
            @empty <tr><td colspan="4" class="text-muted">No refund requests.</td></tr> @endforelse
        </tbody></table></div>
    </div>
    <div class="card" style="grid-column:span 2">
        <h3>Support Tickets</h3>
        <div class="table-responsive"><table class="table"><thead><tr><th>Ticket</th><th>Subject</th><th>Status</th></tr></thead><tbody>
            @forelse($support_tickets as $ticket)
                <tr><td>{{ $ticket->ticket_number }}</td><td>{{ $ticket->subject }}</td><td>{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</td></tr>
            @empty <tr><td colspan="3" class="text-muted">No support tickets.</td></tr> @endforelse
        </tbody></table></div>
    </div>
    <div class="card" style="grid-column:span 2">
        <h3>Recent Attendees / Check-ins</h3>
        <div class="table-responsive"><table class="table"><thead><tr><th>Event</th><th>Code</th><th>Result</th><th>Checked At</th></tr></thead><tbody>
            @forelse($recent_check_ins as $checkIn)
                <tr><td>{{ $checkIn->event?->name ?? '-' }}</td><td>{{ $checkIn->scanned_code }}</td><td>{{ ucfirst(str_replace('_', ' ', $checkIn->result)) }}</td><td>{{ $checkIn->checked_in_at?->format('d M Y, h:i A') }}</td></tr>
            @empty <tr><td colspan="4" class="text-muted">No check-ins yet.</td></tr> @endforelse
        </tbody></table></div>
    </div>
</div>
@endsection
