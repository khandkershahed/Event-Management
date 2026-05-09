<x-admin-app-layout :title="'Sales by Date'">
    <div class="card card-flush">
        <div class="card-header align-items-center py-5">
            <div class="card-title"><h2>Sales by Date</h2></div>
            <a href="{{ route('admin.marketplace-reports.dashboard') }}" class="btn btn-sm btn-light">Back to Dashboard</a>
        </div>
        <div class="card-body">
            @include('admin.pages.marketplace-reports._filters', ['organizers' => $organizers, 'events' => $events])
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed gy-3">
                    <thead><tr class="text-gray-500 fw-bold fs-7 text-uppercase"><th>Date</th><th>Total Orders</th><th>Paid Orders</th><th>Gross Sales</th></tr></thead>
                    <tbody>
                        @forelse($rows as $row)
                            <tr><td>{{ $row->sale_date }}</td><td>{{ number_format($row->total_orders) }}</td><td>{{ number_format($row->paid_orders) }}</td><td>BDT {{ number_format((float) $row->gross_sales, 2) }}</td></tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted">No paid sales found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $rows->links() }}
        </div>
    </div>
</x-admin-app-layout>
