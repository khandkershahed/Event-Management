<x-admin-app-layout :title="'Refund Report'">
    <div class="card card-flush">
        <div class="card-header align-items-center py-5">
            <div class="card-title"><h2>Refund Report</h2></div>
            <a href="{{ route('admin.marketplace-reports.dashboard') }}" class="btn btn-sm btn-light">Back to Dashboard</a>
        </div>
        <div class="card-body">
            @include('admin.pages.marketplace-reports._filters', ['organizers' => $organizers, 'events' => $events, 'statuses' => $statuses])
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed gy-3">
                    <thead><tr class="text-gray-500 fw-bold fs-7 text-uppercase"><th>Order</th><th>Organizer</th><th>Event</th><th>Requested Amount</th><th>Refund Status</th><th>Transaction Status</th><th>Date</th></tr></thead>
                    <tbody>
                        @forelse($rows as $row)
                            @php($latestTransaction = $row->transactions->sortByDesc('created_at')->first())
                            <tr>
                                <td>{{ $row->order?->order_number ?: '#' . $row->order_id }}</td>
                                <td>{{ $row->organizerProfile?->organization_name }}</td>
                                <td>{{ $row->event?->name }}</td>
                                <td>{{ $row->currency }} {{ number_format((float) $row->amount, 2) }}</td>
                                <td><span class="badge badge-light-primary">{{ ucfirst($row->status) }}</span></td>
                                <td>{{ $latestTransaction ? ucfirst($latestTransaction->status) : 'No Transaction' }}</td>
                                <td>{{ optional($row->created_at)->format('d M Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted">No refund requests found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $rows->links() }}
        </div>
    </div>
</x-admin-app-layout>
