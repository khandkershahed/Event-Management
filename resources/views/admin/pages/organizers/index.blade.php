<x-admin-app-layout :title="'Organizers'">
    <div class="card card-flash"><div class="card-body">
        <h3>Organizers</h3>
        <table class="table"><thead><tr><th>Organization</th><th>User</th><th>Status</th><th>Actions</th></tr></thead><tbody>
        @forelse($organizers as $organizer)
            <tr><td>{{ $organizer->organization_name }}</td><td>{{ $organizer->user?->email }}</td><td>{{ ucfirst($organizer->status) }}</td><td><a class="btn btn-sm btn-primary" href="{{ route('admin.organizers.show', $organizer) }}">View</a></td></tr>
        @empty<tr><td colspan="4">No organizers found.</td></tr>@endforelse
        </tbody></table>{{ $organizers->links() }}
    </div></div>
</x-admin-app-layout>
