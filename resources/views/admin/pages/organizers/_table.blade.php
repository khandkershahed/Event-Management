<table class="table table-striped table-row-bordered gy-5 gs-7 border rounded">
    <thead class="bg-dark text-light">
        <tr>
            <th width="5%">Sl.</th>
            <th>Organization</th>
            <th>User</th>
            <th>Status</th>
            <th>Submitted</th>
            <th width="15%">Action</th>
        </tr>
    </thead>
    <tbody class="fw-bold text-gray-700">
        @forelse ($organizers as $key => $organizer)
            <tr>
                <td>{{ $organizers->firstItem() + $key }}</td>
                <td>{{ $organizer->organization_name }}</td>
                <td>{{ optional($organizer->user)->name }}<br><small class="text-muted">{{ optional($organizer->user)->email }}</small></td>
                <td><span class="badge bg-{{ $organizer->status === 'approved' ? 'success' : ($organizer->status === 'pending' ? 'warning' : 'secondary') }}">{{ ucfirst($organizer->status) }}</span></td>
                <td>{{ optional($organizer->submitted_at)->format('Y-m-d H:i') ?: '-' }}</td>
                <td><a href="{{ route('admin.organizers.show', $organizer) }}" class="btn btn-sm btn-light-primary">View</a></td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center text-muted py-5">No organizer profiles found.</td></tr>
        @endforelse
    </tbody>
</table>
{{ $organizers->links() }}
