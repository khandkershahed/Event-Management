<x-frontend-app-layout :title="'My Refunds'">
    @include('user.layout.sidebar')
    <div class="wrapper wrapper-body">
        <div class="dashboard-body">
            <div class="container-fluid">
                <div class="d-main-title mb-4">
                    <h3><i class="fa-solid fa-rotate-left me-3"></i>My Refunds</h3>
                </div>
                <div class="main-card p-4">
                    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Order</th><th>Event</th><th>Amount</th><th>Status</th><th>Requested</th><th>Note</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($refunds as $refund)
                                    <tr>
                                        <td>{{ $refund->order?->order_number }}</td>
                                        <td>{{ $refund->order?->event?->name ?? '-' }}</td>
                                        <td>{{ $refund->currency }} {{ number_format((float) $refund->amount, 2) }}</td>
                                        <td>{{ ucfirst($refund->status) }}</td>
                                        <td>{{ $refund->created_at?->format('d M Y, h:i A') }}</td>
                                        <td>{{ $refund->admin_note ?: '-' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center text-muted py-5">No refund requests found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $refunds->links() }}
                </div>
            </div>
        </div>
    </div>
</x-frontend-app-layout>
