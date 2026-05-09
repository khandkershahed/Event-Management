<x-frontend-app-layout :title="'My Reports'">
    @include('user.layout.sidebar')
    <div class="wrapper wrapper-body"><div class="dashboard-body"><div class="container-fluid">
        <div class="d-main-title mb-4"><h3><i class="fa-solid fa-chart-pie me-3"></i>My Reports</h3><p class="text-muted mb-0">A customer-facing summary generated from your real orders, tickets, saved events, support, and refund data.</p></div>
        <div class="row">
            @include('user.pages.partials.panel-stat', ['label' => 'Orders', 'value' => $summary['orders'] ?? 0, 'icon' => 'fa-receipt'])
            @include('user.pages.partials.panel-stat', ['label' => 'Tickets', 'value' => $summary['tickets'] ?? 0, 'icon' => 'fa-ticket'])
            @include('user.pages.partials.panel-stat', ['label' => 'Support Tickets', 'value' => $summary['support_tickets'] ?? 0, 'icon' => 'fa-headset'])
            @include('user.pages.partials.panel-stat', ['label' => 'Refunds', 'value' => $summary['refunds'] ?? 0, 'icon' => 'fa-rotate-left'])
        </div>
        <div class="row">
            <div class="col-lg-6 mb-4"><div class="main-card p-4 h-100"><h5>Latest Support</h5>@forelse($supportTickets as $ticket)<div class="border rounded p-3 mb-2"><strong>{{ $ticket->ticket_number }}</strong><div>{{ $ticket->subject }}</div><span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</span></div>@empty @include('user.pages.partials.panel-empty', ['message' => 'No support tickets found.', 'url' => route('user.support-tickets.create'), 'label' => 'Create Ticket']) @endforelse</div></div>
            <div class="col-lg-6 mb-4"><div class="main-card p-4 h-100"><h5>Latest Refunds</h5>@forelse($refunds as $refund)<div class="border rounded p-3 mb-2"><strong>{{ $refund->currency }} {{ number_format((float) $refund->amount, 2) }}</strong><div>{{ $refund->order?->event?->name ?? 'Event removed' }}</div><span class="badge bg-secondary">{{ ucfirst($refund->status) }}</span></div>@empty @include('user.pages.partials.panel-empty', ['message' => 'No refund requests found.', 'url' => route('user.orders.index'), 'label' => 'View Orders']) @endforelse</div></div>
        </div>
    </div></div></div>
</x-frontend-app-layout>
