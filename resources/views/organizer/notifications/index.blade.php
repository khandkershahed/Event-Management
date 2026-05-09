@extends('organizer.layouts.app', ['title' => 'Notifications'])

@section('content')
<div class="card">
    <h2>Notifications</h2>
    <p>Important marketplace updates for your organizer account.</p>
</div>

<div class="card">
    <table class="table">
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
                    <td><a class="btn btn-primary" href="{{ route('organizer.notifications.read', $notification) }}">Open</a></td>
                </tr>
            @empty
                <tr><td colspan="5" style="text-align:center;color:#6b7280;padding:24px">No notifications found.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $notifications->links() }}
</div>
@endsection
