<x-admin-app-layout :title="'Refund Requests'">
    <div class="card card-flush">
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <div class="card-title"><h2>Refund Requests</h2></div>
            <form method="GET" class="d-flex gap-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
                <button class="btn btn-sm btn-primary">Filter</button>
            </form>
        </div>
        <div class="card-body">
            @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed gy-3">
                    <thead><tr><th>ID</th><th>Order</th><th>Customer</th><th>Event</th><th>Amount</th><th>Status</th><th>Action</th></tr></thead>
                    <tbody>
                    @forelse($refunds as $refund)
                        <tr>
                            <td>#{{ $refund->id }}</td>
                            <td>{{ $refund->order?->order_number }}</td>
                            <td>{{ $refund->user?->name ?? $refund->order?->customer_name }}</td>
                            <td>{{ $refund->event?->name ?? '-' }}</td>
                            <td>{{ $refund->currency }} {{ number_format((float) $refund->amount, 2) }}</td>
                            <td><span class="badge badge-light-primary">{{ ucfirst($refund->status) }}</span></td>
                            <td><a href="{{ route('admin.refunds.show', $refund) }}" class="btn btn-sm btn-light-primary">Review</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted">No refund requests found.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            {{ $refunds->links() }}
        </div>
    </div>
</x-admin-app-layout>
