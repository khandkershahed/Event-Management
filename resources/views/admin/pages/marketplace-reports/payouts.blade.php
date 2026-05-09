<x-admin-app-layout :title="'Payout Report'">
    <div class="card card-flush">
        <div class="card-header align-items-center py-5">
            <div class="card-title"><h2>Payout Report</h2></div>
            <a href="{{ route('admin.marketplace-reports.dashboard') }}" class="btn btn-sm btn-light">Back to Dashboard</a>
        </div>
        <div class="card-body">
            @include('admin.pages.marketplace-reports._filters', ['organizers' => $organizers, 'statuses' => $statuses])
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed gy-3">
                    <thead><tr class="text-gray-500 fw-bold fs-7 text-uppercase"><th>Payout No.</th><th>Organizer</th><th>Amount</th><th>Status</th><th>Requested</th><th>Approved</th><th>Paid</th></tr></thead>
                    <tbody>
                        @forelse($rows as $row)
                            <tr>
                                <td>{{ $row->payout_number }}</td>
                                <td>{{ $row->organizerProfile?->organization_name }}</td>
                                <td>{{ $row->currency }} {{ number_format((float) $row->amount, 2) }}</td>
                                <td><span class="badge badge-light-primary">{{ ucfirst($row->status) }}</span></td>
                                <td>{{ optional($row->requested_at)->format('d M Y H:i') }}</td>
                                <td>{{ optional($row->approved_at)->format('d M Y H:i') }}</td>
                                <td>{{ optional($row->paid_at)->format('d M Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted">No payout records found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $rows->links() }}
        </div>
    </div>
</x-admin-app-layout>
