<x-admin-app-layout :title="'Event Cancellation Requests'">
    <div class="card card-flush">
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <div class="card-title"><h2>Event Cancellation Requests</h2></div>
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
                    <thead><tr><th>ID</th><th>Event</th><th>Organizer</th><th>Status</th><th>Requested</th><th>Action</th></tr></thead>
                    <tbody>
                    @forelse($requests as $request)
                        <tr>
                            <td>#{{ $request->id }}</td>
                            <td>{{ $request->event?->name }}</td>
                            <td>{{ $request->organizerProfile?->organization_name }}</td>
                            <td><span class="badge badge-light-primary">{{ ucfirst($request->status) }}</span></td>
                            <td>{{ $request->created_at?->format('d M Y H:i') }}</td>
                            <td><a href="{{ route('admin.event-cancellations.show', $request) }}" class="btn btn-sm btn-light-primary">Review</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted">No cancellation requests found.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            {{ $requests->links() }}
        </div>
    </div>
</x-admin-app-layout>
