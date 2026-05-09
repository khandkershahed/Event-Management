<x-admin-app-layout :title="'Marketplace Reporting Dashboard'">
    <div class="mb-5">
        <h1 class="fs-2 fw-bold mb-1">Marketplace Reporting Dashboard</h1>
        <p class="text-muted mb-0">Platform-level overview of organizers, events, orders, sales, commissions, payouts, refunds, and cancellations.</p>
    </div>

    @include('admin.pages.marketplace-reports._filters', ['organizers' => $organizers, 'events' => $events])

    <div class="row g-5 g-xl-8">
        @php
            $cards = [
                ['label' => 'Total Organizers', 'value' => number_format($kpis['total_organizers'])],
                ['label' => 'Pending Organizers', 'value' => number_format($kpis['pending_organizers'])],
                ['label' => 'Total Events', 'value' => number_format($kpis['total_events'])],
                ['label' => 'Pending Event Approvals', 'value' => number_format($kpis['pending_event_approvals'])],
                ['label' => 'Published Events', 'value' => number_format($kpis['published_events'])],
                ['label' => 'Total Orders', 'value' => number_format($kpis['total_orders'])],
                ['label' => 'Paid Orders', 'value' => number_format($kpis['paid_orders'])],
                ['label' => 'Gross Sales', 'value' => 'BDT ' . number_format((float) $kpis['gross_sales'], 2)],
                ['label' => 'Platform Commission', 'value' => 'BDT ' . number_format((float) $kpis['platform_commission'], 2)],
                ['label' => 'Pending Payouts', 'value' => 'BDT ' . number_format((float) $kpis['pending_payouts'], 2)],
                ['label' => 'Refund Requests', 'value' => number_format($kpis['refund_requests'])],
                ['label' => 'Event Cancellation Requests', 'value' => number_format($kpis['event_cancellation_requests'])],
            ];
        @endphp

        @foreach($cards as $card)
            <div class="col-md-3">
                <div class="card card-flush h-100">
                    <div class="card-body">
                        <div class="text-gray-600 fw-semibold fs-7 text-uppercase mb-2">{{ $card['label'] }}</div>
                        <div class="fs-2 fw-bold text-gray-900">{{ $card['value'] }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card card-flush mt-8">
        <div class="card-header"><h3 class="card-title">Report Shortcuts</h3></div>
        <div class="card-body d-flex flex-wrap gap-3">
            <a href="{{ route('admin.marketplace-reports.sales-by-date') }}" class="btn btn-light-primary">Sales by Date</a>
            <a href="{{ route('admin.marketplace-reports.sales-by-organizer') }}" class="btn btn-light-primary">Sales by Organizer</a>
            <a href="{{ route('admin.marketplace-reports.sales-by-event') }}" class="btn btn-light-primary">Sales by Event</a>
            <a href="{{ route('admin.marketplace-reports.commissions') }}" class="btn btn-light-primary">Commissions</a>
            <a href="{{ route('admin.marketplace-reports.payouts') }}" class="btn btn-light-primary">Payout Reports</a>
            <a href="{{ route('admin.marketplace-reports.refunds') }}" class="btn btn-light-primary">Refund Reports</a>
        </div>
    </div>
</x-admin-app-layout>
