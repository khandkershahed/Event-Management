<x-admin-app-layout :title="'Payout Methods'">
    <div class="card card-flush">
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <div class="card-title"><h2>Payout Methods</h2></div>
            <form method="GET" class="d-flex gap-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                    @endforeach
                </select>
                <select name="method_type" class="form-select form-select-sm">
                    <option value="">All Methods</option>
                    @foreach($methodTypes as $type)
                        <option value="{{ $type }}" @selected(request('method_type') === $type)>{{ ucfirst(str_replace('_', ' ', $type)) }}</option>
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
                            <th>Organizer</th>
                            <th>Method</th>
                            <th>Status</th>
                            <th>Active</th>
                            <th>Submitted</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($methods as $method)
                            <tr>
                                <td>{{ $method->organizerProfile?->organization_name }}</td>
                                <td>{{ $method->methodLabel() }}</td>
                                <td><span class="badge badge-light-primary">{{ $method->statusLabel() }}</span></td>
                                <td>{{ $method->is_active ? 'Yes' : 'No' }}</td>
                                <td>{{ optional($method->submitted_at)->format('d M Y H:i') }}</td>
                                <td><a href="{{ route('admin.payout-methods.show', $method) }}" class="btn btn-sm btn-light-primary">Review</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted">No payout methods found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $methods->links() }}
        </div>
    </div>
</x-admin-app-layout>
