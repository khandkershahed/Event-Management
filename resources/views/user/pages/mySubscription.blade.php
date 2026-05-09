<x-frontend-app-layout :title="'Account Preferences'">
    @include('user.layout.sidebar')
    <div class="wrapper wrapper-body"><div class="dashboard-body"><div class="container-fluid">
        <div class="d-main-title mb-4"><h3><i class="fa-solid fa-bahai me-3"></i>Account Preferences</h3><p class="text-muted mb-0">This page is backed by your real notification and marketplace activity instead of static subscription demos.</p></div>
        <div class="row">
            @include('user.pages.partials.panel-stat', ['label' => 'Unread Notifications', 'value' => $summary['unread_notifications'] ?? 0, 'icon' => 'fa-bell'])
            @include('user.pages.partials.panel-stat', ['label' => 'Followed Organizers', 'value' => $summary['followed_organizers'] ?? 0, 'icon' => 'fa-user-check'])
            @include('user.pages.partials.panel-stat', ['label' => 'Saved Events', 'value' => $summary['saved_events'] ?? 0, 'icon' => 'fa-bookmark'])
            @include('user.pages.partials.panel-stat', ['label' => 'Reviews', 'value' => $summary['reviews'] ?? 0, 'icon' => 'fa-star'])
        </div>
        <div class="main-card p-4">
            <h5 class="mb-3">Notification Center</h5>
            @forelse ($notifications as $notification)
                <div class="border rounded p-3 mb-2 d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ $notification->data['title'] ?? 'Marketplace notification' }}</strong>
                        <div class="small text-muted">{{ $notification->data['message'] ?? ($notification->data['body'] ?? 'Open notification for details.') }}</div>
                    </div>
                    <a href="{{ route('user.notifications.read', $notification) }}" class="btn btn-sm btn-outline-primary">Open</a>
                </div>
            @empty
                @include('user.pages.partials.panel-empty', ['message' => 'No notifications found.', 'url' => route('all.events'), 'label' => 'Discover Events'])
            @endforelse
        </div>
    </div></div></div>
</x-frontend-app-layout>
