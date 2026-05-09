<x-admin-app-layout :title="'Sales by Event'">
    <div class="card card-flush">
        <div class="card-header align-items-center py-5">
            <div class="card-title"><h2>Sales by Event</h2></div>
            <a href="{{ route('admin.marketplace-reports.dashboard') }}" class="btn btn-sm btn-light">Back to Dashboard</a>
        </div>
        <div class="card-body">
            @include('admin.pages.marketplace-reports._filters', ['organizers' => $organizers, 'events' => $events])
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed gy-3">
                    <thead><tr class="text-gray-500 fw-bold fs-7 text-uppercase"><th>Event</th><th>Organizer</th><th>Paid Orders</th><th>Tickets Sold/Issued</th><th>Gross Sales</th></tr></thead>
                    <tbody>
                        @forelse($rows as $row)
                            <tr>
                                <td>{{ $row->event_title ?: 'Untitled Event' }}</td>
                                <td>{{ $row->organizer_name ?: 'Unassigned Organizer' }}</td>
                                <td>{{ number_format($row->paid_orders) }}</td>
                                <td>{{ number_format($row->tickets_sold) }}</td>
                                <td>BDT {{ number_format((float) $row->gross_sales, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted">No event sales found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $rows->links() }}
        </div>
    </div>
</x-admin-app-layout>
