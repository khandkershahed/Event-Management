<x-admin-app-layout :title="'Commission Report'">
    <div class="card card-flush">
        <div class="card-header align-items-center py-5">
            <div class="card-title"><h2>Commission Report</h2></div>
            <a href="{{ route('admin.marketplace-reports.dashboard') }}" class="btn btn-sm btn-light">Back to Dashboard</a>
        </div>
        <div class="card-body">
            @include('admin.pages.marketplace-reports._filters', ['organizers' => $organizers, 'events' => $events, 'statuses' => $statuses])
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed gy-3">
                    <thead><tr class="text-gray-500 fw-bold fs-7 text-uppercase"><th>Order</th><th>Organizer</th><th>Event</th><th>Gross</th><th>Commission</th><th>Status</th><th>Date</th></tr></thead>
                    <tbody>
                        @forelse($rows as $row)
                            <tr>
                                <td>{{ $row->order?->order_number ?: '#' . $row->order_id }}</td>
                                <td>{{ $row->organizerProfile?->organization_name }}</td>
                                <td>{{ $row->order?->event?->name }}</td>
                                <td>{{ $row->currency }} {{ number_format((float) $row->gross_amount, 2) }}</td>
                                <td>{{ $row->currency }} {{ number_format((float) $row->commission_amount, 2) }}</td>
                                <td><span class="badge badge-light-primary">{{ ucfirst($row->status) }}</span></td>
                                <td>{{ optional($row->posted_at ?? $row->created_at)->format('d M Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted">No commission ledger entries found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $rows->links() }}
        </div>
    </div>
</x-admin-app-layout>
