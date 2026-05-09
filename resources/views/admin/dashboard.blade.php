<x-admin-app-layout :title="'Admin Dashboard'">
    @php
        $money = fn ($value) => 'BDT ' . number_format((float) $value, 2);
        $statCards = [
            ['label' => 'Total Users', 'value' => number_format($stats['total_users']), 'icon' => 'fa-users'],
            ['label' => 'Total Organizers', 'value' => number_format($stats['total_organizers']), 'icon' => 'fa-building-user'],
            ['label' => 'Pending Organizers', 'value' => number_format($stats['pending_organizers']), 'icon' => 'fa-user-clock'],
            ['label' => 'Approved Organizers', 'value' => number_format($stats['approved_organizers']), 'icon' => 'fa-user-check'],
            ['label' => 'Total Events', 'value' => number_format($stats['total_events']), 'icon' => 'fa-calendar-days'],
            ['label' => 'Pending Events', 'value' => number_format($stats['pending_events']), 'icon' => 'fa-hourglass-half'],
            ['label' => 'Published Events', 'value' => number_format($stats['published_events']), 'icon' => 'fa-calendar-check'],
            ['label' => 'Total Orders', 'value' => number_format($stats['total_orders']), 'icon' => 'fa-receipt'],
            ['label' => 'Paid Orders', 'value' => number_format($stats['paid_orders']), 'icon' => 'fa-circle-check'],
            ['label' => 'Pending Payments', 'value' => number_format($stats['pending_payment_orders']), 'icon' => 'fa-credit-card'],
            ['label' => 'Issued Tickets', 'value' => number_format($stats['issued_tickets']), 'icon' => 'fa-ticket'],
            ['label' => 'Gross Sales', 'value' => $money($stats['gross_sales']), 'icon' => 'fa-chart-line'],
            ['label' => 'Platform Commission', 'value' => $money($stats['platform_commission']), 'icon' => 'fa-percent'],
            ['label' => 'Refund Amount', 'value' => $money($stats['refunds']), 'icon' => 'fa-rotate-left'],
            ['label' => 'Pending Refunds', 'value' => number_format($stats['pending_refunds']), 'icon' => 'fa-hand-holding-dollar'],
            ['label' => 'Pending Payouts', 'value' => number_format($stats['pending_payouts']), 'icon' => 'fa-money-bill-transfer'],
            ['label' => 'Open Support', 'value' => number_format($stats['open_support_tickets']), 'icon' => 'fa-headset'],
            ['label' => 'Moderation Flags', 'value' => number_format($stats['moderation_flags']), 'icon' => 'fa-flag'],
            ['label' => 'Reviews', 'value' => number_format($stats['reviews']), 'icon' => 'fa-star'],
            ['label' => 'Unread Notifications', 'value' => number_format($stats['unread_admin_notifications']), 'icon' => 'fa-bell'],
        ];
        $maxSale = max(1, collect($sales_chart)->max('sales'));
    @endphp

    <div class="py-4 container-fluid">
        <div class="mb-4 row">
            <div class="col-12">
                <div class="p-4 shadow-sm card rounded-4">
                    <div class="d-flex flex-column flex-lg-row justify-content-between gap-3">
                        <div>
                            <h2 class="mb-1 fw-bold">Admin Marketplace Operations Dashboard</h2>
                            <p class="mb-0 text-muted">Live overview of organizers, events, orders, tickets, commissions, payouts, refunds, support, moderation, and reviews.</p>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('admin.organizers.pending') }}" class="btn btn-sm btn-warning">Organizer Approvals</a>
                            <a href="{{ route('admin.event-approvals.index') }}" class="btn btn-sm btn-primary">Event Approvals</a>
                            <a href="{{ route('admin.marketplace-reports.dashboard') }}" class="btn btn-sm btn-info">Reports</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-5" aria-label="Admin dashboard real stats">
            @foreach($statCards as $card)
                <div class="col-sm-6 col-xl-3">
                    <div class="p-4 shadow-sm card h-100 rounded-4">
                        <div class="d-flex align-items-center justify-content-between gap-3">
                            <div>
                                <div class="text-muted small">{{ $card['label'] }}</div>
                                <div class="fs-3 fw-bold">{{ $card['value'] }}</div>
                            </div>
                            <i class="fa-solid {{ $card['icon'] }} fs-2 text-primary"></i>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row g-4 mb-5">
            <div class="col-xl-8">
                <div class="p-4 shadow-sm card h-100 rounded-4">
                    <h4 class="mb-3">Last 30 Days Sales</h4>
                    <div class="d-flex align-items-end gap-1" style="height:180px" aria-label="last 30 days sales chart style block">
                        @foreach($sales_chart as $day)
                            <div title="{{ $day['label'] }}: {{ $money($day['sales']) }}" class="bg-primary rounded-top flex-fill" style="height: {{ max(5, round(($day['sales'] / $maxSale) * 170)) }}px"></div>
                        @endforeach
                    </div>
                    <div class="text-muted small mt-2">Each bar represents paid sales for one day.</div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="p-4 shadow-sm card h-100 rounded-4">
                    <h4 class="mb-3">Status Summary</h4>
                    <h6>Orders by Status</h6>
                    @forelse($orders_by_status as $status => $count)
                        <div class="d-flex justify-content-between border-bottom py-2"><span>{{ ucfirst(str_replace('_', ' ', $status)) }}</span><strong>{{ $count }}</strong></div>
                    @empty
                        <p class="text-muted">No order data yet.</p>
                    @endforelse
                    <h6 class="mt-4">Events by Status</h6>
                    @forelse($events_by_status as $status => $count)
                        <div class="d-flex justify-content-between border-bottom py-2"><span>{{ ucfirst(str_replace('_', ' ', $status)) }}</span><strong>{{ $count }}</strong></div>
                    @empty
                        <p class="text-muted">No event data yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-lg-3"><div class="p-4 shadow-sm card rounded-4"><div class="text-muted">Pending Payouts</div><h3>{{ $payout_refund_summary['pending_payouts'] }}</h3></div></div>
            <div class="col-lg-3"><div class="p-4 shadow-sm card rounded-4"><div class="text-muted">Paid Payouts</div><h3>{{ $payout_refund_summary['paid_payouts'] }}</h3></div></div>
            <div class="col-lg-3"><div class="p-4 shadow-sm card rounded-4"><div class="text-muted">Pending Refunds</div><h3>{{ $payout_refund_summary['pending_refunds'] }}</h3></div></div>
            <div class="col-lg-3"><div class="p-4 shadow-sm card rounded-4"><div class="text-muted">Approved Refunds</div><h3>{{ $payout_refund_summary['approved_refunds'] }}</h3></div></div>
        </div>

        <div class="row g-4">
            <div class="col-xl-6">
                <div class="p-4 shadow-sm card h-100 rounded-4">
                    <h4>Recent Orders</h4>
                    <div class="table-responsive"><table class="table align-middle"><thead><tr><th>Order</th><th>Event</th><th>Total</th><th>Status</th></tr></thead><tbody>
                        @forelse($recent_orders as $order)
                            <tr><td>{{ $order->order_number }}</td><td>{{ $order->event?->name ?? '-' }}</td><td>{{ $order->currency }} {{ number_format((float) $order->total, 2) }}</td><td>{{ ucfirst(str_replace('_', ' ', $order->status)) }}</td></tr>
                        @empty <tr><td colspan="4" class="text-muted text-center">No recent orders.</td></tr> @endforelse
                    </tbody></table></div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="p-4 shadow-sm card h-100 rounded-4">
                    <h4>Pending Organizer Approvals</h4>
                    <div class="table-responsive"><table class="table align-middle"><thead><tr><th>Organizer</th><th>Email</th><th>Submitted</th></tr></thead><tbody>
                        @forelse($pending_organizers as $organizer)
                            <tr><td>{{ $organizer->organization_name }}</td><td>{{ $organizer->email ?: $organizer->user?->email }}</td><td>{{ $organizer->submitted_at?->format('d M Y') ?: '-' }}</td></tr>
                        @empty <tr><td colspan="3" class="text-muted text-center">No pending organizers.</td></tr> @endforelse
                    </tbody></table></div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="p-4 shadow-sm card h-100 rounded-4"><h4>Pending Event Approvals</h4><div class="table-responsive"><table class="table align-middle"><thead><tr><th>Event</th><th>Organizer</th><th>Status</th></tr></thead><tbody>
                    @forelse($pending_events as $event)
                        <tr><td>{{ $event->name }}</td><td>{{ $event->organizerProfile?->organization_name ?? '-' }}</td><td>{{ ucfirst(str_replace('_', ' ', $event->status)) }}</td></tr>
                    @empty <tr><td colspan="3" class="text-muted text-center">No pending events.</td></tr> @endforelse
                </tbody></table></div></div>
            </div>
            <div class="col-xl-6">
                <div class="p-4 shadow-sm card h-100 rounded-4"><h4>Pending Refunds</h4><div class="table-responsive"><table class="table align-middle"><thead><tr><th>Order</th><th>Customer</th><th>Amount</th></tr></thead><tbody>
                    @forelse($pending_refunds as $refund)
                        <tr><td>{{ $refund->order?->order_number ?? '-' }}</td><td>{{ $refund->user?->name ?? '-' }}</td><td>{{ $refund->currency }} {{ number_format((float) $refund->amount, 2) }}</td></tr>
                    @empty <tr><td colspan="3" class="text-muted text-center">No pending refunds.</td></tr> @endforelse
                </tbody></table></div></div>
            </div>
            <div class="col-xl-6">
                <div class="p-4 shadow-sm card h-100 rounded-4"><h4>Pending Payouts</h4><div class="table-responsive"><table class="table align-middle"><thead><tr><th>Payout</th><th>Organizer</th><th>Amount</th></tr></thead><tbody>
                    @forelse($pending_payouts as $payout)
                        <tr><td>{{ $payout->payout_number }}</td><td>{{ $payout->organizerProfile?->organization_name ?? '-' }}</td><td>{{ $payout->currency }} {{ number_format((float) $payout->amount, 2) }}</td></tr>
                    @empty <tr><td colspan="3" class="text-muted text-center">No pending payouts.</td></tr> @endforelse
                </tbody></table></div></div>
            </div>
            <div class="col-xl-6">
                <div class="p-4 shadow-sm card h-100 rounded-4"><h4>Open Support Tickets</h4><div class="table-responsive"><table class="table align-middle"><thead><tr><th>Ticket</th><th>Subject</th><th>Status</th></tr></thead><tbody>
                    @forelse($open_support_tickets as $ticket)
                        <tr><td>{{ $ticket->ticket_number }}</td><td>{{ $ticket->subject }}</td><td>{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</td></tr>
                    @empty <tr><td colspan="3" class="text-muted text-center">No open support tickets.</td></tr> @endforelse
                </tbody></table></div></div>
            </div>
        </div>
    </div>
</x-admin-app-layout>
