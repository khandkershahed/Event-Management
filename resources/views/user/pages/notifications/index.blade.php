<x-frontend-app-layout :title="'My Notifications'">
    @include('user.layout.sidebar')
    <div class="wrapper wrapper-body">
        <div class="dashboard-body">
            <div class="container-fluid">
                <div class="d-main-title mb-4">
                    <h3><i class="fa-solid fa-bell me-3"></i>My Notifications</h3>
                </div>
                <div class="main-card p-4">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
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
                                        <td><strong>{{ $notification->data['title'] ?? class_basename($notification->type) }}</strong></td>
                                        <td>{{ $notification->data['message'] ?? 'Notification received.' }}</td>
                                        <td>{{ $notification->read_at ? 'Read' : 'Unread' }}</td>
                                        <td>{{ $notification->created_at?->format('d M Y, h:i A') }}</td>
                                        <td><a class="btn btn-sm btn-primary" href="{{ route('user.notifications.read', $notification) }}">Open</a></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted py-5">No notifications found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $notifications->links() }}
                </div>
            </div>
        </div>
    </div>
</x-frontend-app-layout>
