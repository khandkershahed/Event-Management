<x-frontend-app-layout :title="'My Orders'">
    @include('user.layout.sidebar')
    <div class="wrapper wrapper-body">
        <div class="dashboard-body">
            <div class="container-fluid">
                <div class="d-main-title mb-4">
                    <h3><i class="fa-solid fa-receipt me-3"></i>My Orders</h3>
                </div>
                <div class="main-card p-4">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Order Number</th>
                                    <th>Event</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Payment</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($orders as $order)
                                    <tr>
                                        <td><strong>{{ $order->order_number }}</strong></td>
                                        <td>{{ $order->event?->name ?? 'Event removed' }}</td>
                                        <td>{{ $order->currency }} {{ number_format((float) $order->total, 2) }}</td>
                                        <td>{{ ucfirst(str_replace('_', ' ', $order->status)) }}</td>
                                        <td>{{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}</td>
                                        <td>{{ $order->created_at?->format('d M Y, h:i A') }}</td>
                                        <td><a href="{{ route('user.orders.show', $order) }}" class="btn btn-sm btn-primary">View</a></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center text-muted py-5">No orders found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</x-frontend-app-layout>
