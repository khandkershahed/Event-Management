<x-admin-app-layout :title="'Sales by Organizer'">
    <div class="card card-flush">
        <div class="card-header align-items-center py-5">
            <div class="card-title"><h2>Sales by Organizer</h2></div>
            <a href="{{ route('admin.marketplace-reports.dashboard') }}" class="btn btn-sm btn-light">Back to Dashboard</a>
        </div>
        <div class="card-body">
            @include('admin.pages.marketplace-reports._filters', ['organizers' => $organizers, 'events' => $events])
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed gy-3">
                    <thead><tr class="text-gray-500 fw-bold fs-7 text-uppercase"><th>Organizer</th><th>Total Events</th><th>Paid Orders</th><th>Gross Sales</th><th>Commission</th></tr></thead>
                    <tbody>
                        @forelse($rows as $row)
                            <tr>
                                <td>{{ $row->organizer_name ?: 'Unassigned Organizer' }}</td>
                                <td>{{ number_format($row->total_events) }}</td>
                                <td>{{ number_format($row->paid_orders) }}</td>
                                <td>BDT {{ number_format((float) $row->gross_sales, 2) }}</td>
                                <td>BDT {{ number_format((float) $row->platform_commission, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted">No organizer sales found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $rows->links() }}
        </div>
    </div>
</x-admin-app-layout>
