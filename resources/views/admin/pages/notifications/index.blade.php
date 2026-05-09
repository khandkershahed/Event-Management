<x-admin-app-layout :title="'Admin Notifications'">
    <div class="mb-5">
        <h1 class="fs-2 fw-bold mb-1">Admin Notifications</h1>
        <p class="text-muted mb-0">System and marketplace notifications for the admin account.</p>
    </div>

    <div class="card card-flush">
        <div class="card-body table-responsive">
            <table class="table align-middle table-row-dashed">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th>Title</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($notifications as $notification)
                        <tr>
                            <td class="fw-semibold">{{ $notification->data['title'] ?? class_basename($notification->type) }}</td>
                            <td>{{ $notification->data['message'] ?? 'Notification received.' }}</td>
                            <td>{{ $notification->read_at ? 'Read' : 'Unread' }}</td>
                            <td>{{ $notification->created_at?->format('d M Y, h:i A') }}</td>
                            <td><a class="btn btn-sm btn-light-primary" href="{{ route('admin.notifications.read', $notification) }}">Open</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-8">No notifications found.</td></tr>
                    @endforelse
                </tbody>
            </table>

            {{ $notifications->links() }}
        </div>
    </div>
</x-admin-app-layout>
