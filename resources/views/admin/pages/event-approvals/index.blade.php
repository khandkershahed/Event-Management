<x-admin-app-layout :title="'Event Approvals'">
    <div class="card card-flash">
        <div class="card-header mt-6 d-flex justify-content-between align-items-center">
            <div class="card-title"><h3 class="fw-bold mb-0">Pending Event Approvals</h3></div>
            <div class="card-toolbar"><a href="{{ route('admin.event.index') }}" class="btn btn-light-primary">All Events</a></div>
        </div>
        <div class="card-body pt-0">
            <table class="table table-striped table-row-bordered gy-5 gs-7 border rounded">
                <thead class="bg-dark text-light"><tr><th>Event</th><th>Organizer</th><th>Venue</th><th>Submitted</th><th>Status</th><th>Action</th></tr></thead>
                <tbody class="fw-bold text-gray-700">
                @forelse($events as $event)
                    <tr>
                        <td>{{ $event->name }}</td>
                        <td>{{ optional($event->organizerProfile)->organization_name ?? '-' }}</td>
                        <td>{{ optional($event->venueModel)->name ?? $event->venue ?? '-' }}</td>
                        <td>{{ $event->submitted_at ? $event->submitted_at->format('Y-m-d H:i') : '-' }}</td>
                        <td><span class="badge bg-warning text-dark">{{ ucwords(str_replace('_', ' ', $event->status)) }}</span></td>
                        <td><a class="btn btn-sm btn-primary" href="{{ route('admin.event-approvals.show', $event) }}">Review</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center">No events are waiting for approval.</td></tr>
                @endforelse
                </tbody>
            </table>
            {{ $events->links() }}
        </div>
    </div>
</x-admin-app-layout>
