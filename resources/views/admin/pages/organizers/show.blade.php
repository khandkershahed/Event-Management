<x-admin-app-layout :title="'Organizer Details'">
    <div class="card card-flash"><div class="card-body">
        <h3>{{ $organizer->organization_name }}</h3>
        <p><strong>User:</strong> {{ $organizer->user?->email }}</p><p><strong>Status:</strong> {{ ucfirst($organizer->status) }}</p><p>{{ $organizer->description }}</p>
        <form method="POST" action="{{ route('admin.organizers.approve', $organizer) }}" style="display:inline">@csrf<button class="btn btn-success">Approve</button></form>
        <form method="POST" action="{{ route('admin.organizers.suspend', $organizer) }}" style="display:inline">@csrf<button class="btn btn-warning">Suspend</button></form>
        <form method="POST" action="{{ route('admin.organizers.reject', $organizer) }}" class="mt-4">@csrf<textarea name="rejection_reason" class="form-control" placeholder="Reason" required></textarea><button class="btn btn-danger mt-2">Reject</button></form>
        <div class="mt-4 border rounded p-4">
            <h5>Moderation Flag</h5>
            <form method="POST" action="{{ route('admin.moderation-flags.organizers.flag', $organizer) }}">
                @csrf
                <textarea name="reason" class="form-control mb-2" placeholder="Reason for flagging this organizer" required></textarea>
                <textarea name="admin_note" class="form-control mb-2" placeholder="Internal admin note (optional)"></textarea>
                <button class="btn btn-light-danger">Flag Organizer</button>
            </form>
        </div>
    </div></div>
</x-admin-app-layout>
