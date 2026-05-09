<x-frontend-app-layout :title="'Payment Activity'">
    @include('user.layout.sidebar')
    <div class="wrapper wrapper-body"><div class="dashboard-body"><div class="container-fluid">
        <div class="d-main-title mb-4"><h3><i class="fa-solid fa-credit-card me-3"></i>Payment Activity</h3><p class="text-muted mb-0">Customer payment history is shown through real order records. No fake card data is displayed.</p></div>
        <div class="row">
            @include('user.pages.partials.panel-stat', ['label' => 'Orders', 'value' => $summary['orders'] ?? 0, 'icon' => 'fa-receipt'])
            @include('user.pages.partials.panel-stat', ['label' => 'Refunds', 'value' => $summary['refunds'] ?? 0, 'icon' => 'fa-rotate-left'])
            @include('user.pages.partials.panel-stat', ['label' => 'Pending Refunds', 'value' => $summary['pending_refunds'] ?? 0, 'icon' => 'fa-clock'])
            @include('user.pages.partials.panel-stat', ['label' => 'Tickets', 'value' => $summary['tickets'] ?? 0, 'icon' => 'fa-ticket'])
        </div>
        <div class="main-card p-4">
            <div class="d-flex justify-content-between align-items-center mb-3"><h5 class="mb-0">Recent Orders</h5><a href="{{ route('user.orders.index') }}" class="btn btn-sm btn-outline-primary">All Orders</a></div>
            <div class="table-responsive"><table class="table align-middle"><thead><tr><th>Order</th><th>Event</th><th>Total</th><th>Status</th><th>Action</th></tr></thead><tbody>
                @forelse ($orders as $order)
                    <tr><td>{{ $order->order_number }}</td><td>{{ $order->event?->name ?? 'Event removed' }}</td><td>{{ $order->currency }} {{ number_format((float) $order->total, 2) }}</td><td>{{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}</td><td><a href="{{ route('user.orders.show', $order) }}" class="btn btn-sm btn-primary">View</a></td></tr>
                @empty
                    <tr><td colspan="5">@include('user.pages.partials.panel-empty', ['message' => 'No payment activity yet.', 'url' => route('all.events'), 'label' => 'Browse Events'])</td></tr>
                @endforelse
            </tbody></table></div>
        </div>
    </div></div></div>
</x-frontend-app-layout>
