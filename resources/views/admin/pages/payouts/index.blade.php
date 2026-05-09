<x-admin-app-layout :title="'Payout Requests'">
    <div class="card card-flush">
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <div class="card-title"><h2>Payout Requests</h2></div>
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
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed gy-3">
                    <thead>
                        <tr class="text-gray-500 fw-bold fs-7 text-uppercase">
                            <th>No.</th>
                            <th>Organizer</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Requested</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payouts as $payout)
                            <tr>
                                <td>{{ $payout->payout_number }}</td>
                                <td>{{ $payout->organizerProfile?->organization_name }}</td>
                                <td>{{ $payout->currency }} {{ number_format((float) $payout->amount, 2) }}</td>
                                <td><span class="badge badge-light-primary">{{ ucfirst($payout->status) }}</span></td>
                                <td>{{ optional($payout->requested_at)->format('d M Y H:i') }}</td>
                                <td><a href="{{ route('admin.payouts.show', $payout) }}" class="btn btn-sm btn-light-primary">View</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted">No payout requests found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $payouts->links() }}
        </div>
    </div>
</x-admin-app-layout>
