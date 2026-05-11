@php
    $summary = $panel['summary'];
    $actions = $panel['actions'];
    $checklist = $panel['checklist'];
    $warnings = $panel['warnings'];
@endphp
<div class="card">
    <div style="display:flex;justify-content:space-between;gap:16px;align-items:flex-start;flex-wrap:wrap">
        <div>
            <h2 style="margin-top:0">{{ $event->name ?: 'Untitled Event' }}</h2>
            <p style="margin:0;color:#64748b">One simple place to manage event details, media, venue, seating, tickets, sales, attendees, and check-in.</p>
        </div>
        <span style="background:#111827;color:#fff;border-radius:999px;padding:8px 12px">{{ $summary['status'] }}</span>
    </div>
</div>

@if(count($warnings))
    <div class="card" style="border-left:4px solid #f59e0b">
        <h3>Setup Warnings</h3>
        <ul>
            @foreach($warnings as $warning)
                <li>{{ $warning }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid">
    <div class="card"><strong>Venue</strong><br>{{ $summary['venue'] }}</div>
    <div class="card"><strong>Seating Plan</strong><br>{{ $summary['seating_plan'] }}</div>
    <div class="card"><strong>Ticket Types</strong><br>{{ $summary['tickets_count'] }}</div>
    <div class="card"><strong>Orders</strong><br>{{ $summary['orders_count'] }} total / {{ $summary['paid_orders_count'] }} paid</div>
    <div class="card"><strong>Issued Tickets</strong><br>{{ $summary['issued_tickets_count'] }}</div>
    <div class="card"><strong>Check-ins</strong><br>{{ $summary['checked_in_count'] }}</div>
    <div class="card"><strong>Gross Sales</strong><br>{{ $summary['gross_sales'] }}</div>
    <div class="card"><strong>Refund / Cancellation</strong><br>{{ $summary['refunds_count'] }} refunds / {{ $summary['cancellations_count'] }} cancellations</div>
</div>

<div class="card">
    <h3>Guided Event Setup Checklist</h3>
    <div class="grid">
        @foreach($checklist as $item)
            <div style="border:1px solid #e5e7eb;border-radius:10px;padding:14px;background:{{ $item['done'] ? '#f0fdf4' : '#fff7ed' }}">
                <strong>{{ $item['done'] ? '✓' : '!' }} {{ $item['label'] }}</strong>
                <div style="font-size:13px;color:#64748b;margin-top:4px">{{ $item['done'] ? 'Completed' : 'Needs attention' }}</div>
            </div>
        @endforeach
    </div>
</div>

<div class="card">
    <h3>Event Control Actions</h3>
    <div class="grid">
        @foreach($actions as $action)
            <div style="border:1px solid #e5e7eb;border-radius:10px;padding:14px">
                <strong>{{ $action['label'] }}</strong>
                <div style="margin-top:10px">
                    @if(! empty($action['url']))
                        <a class="btn {{ !empty($action['primary']) ? 'btn-primary' : 'btn-light' }}" href="{{ $action['url'] }}">Open</a>
                    @else
                        <button class="btn btn-light" disabled>Available after setup</button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
