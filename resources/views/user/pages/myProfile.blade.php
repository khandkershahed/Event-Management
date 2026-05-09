<x-frontend-app-layout :title="'My Profile'">
    @include('user.layout.sidebar')
    <div class="wrapper wrapper-body"><div class="dashboard-body"><div class="container-fluid">
        <div class="d-main-title mb-4"><h3><i class="fa-solid fa-user me-3"></i>My Profile</h3><p class="text-muted mb-0">Manage your profile and view account activity from real marketplace records.</p></div>
        <div class="row">
            <div class="col-lg-4 mb-4"><div class="main-card p-4 h-100"><h5>Account</h5><p class="mb-1"><strong>Name:</strong> {{ $user->name }}</p><p class="mb-1"><strong>Email:</strong> {{ $user->email }}</p><p class="mb-1"><strong>Registered:</strong> {{ $user->created_at?->format('d M Y') }}</p><a href="{{ route('profile.edit') }}" class="btn btn-primary btn-sm mt-3">Update Profile / Password</a></div></div>
            <div class="col-lg-8 mb-4"><div class="row">
                @include('user.pages.partials.panel-stat', ['label' => 'Orders', 'value' => $summary['orders'] ?? 0, 'icon' => 'fa-receipt'])
                @include('user.pages.partials.panel-stat', ['label' => 'Tickets', 'value' => $summary['tickets'] ?? 0, 'icon' => 'fa-ticket'])
                @include('user.pages.partials.panel-stat', ['label' => 'Reviews', 'value' => $summary['reviews'] ?? 0, 'icon' => 'fa-star'])
                @include('user.pages.partials.panel-stat', ['label' => 'Notifications', 'value' => $summary['unread_notifications'] ?? 0, 'icon' => 'fa-bell'])
            </div></div>
        </div>
        <div class="main-card p-4"><h5>Recent Reviews</h5>@forelse($reviews as $review)<div class="border rounded p-3 mb-2"><strong>{{ $review->event?->name ?? 'Event removed' }}</strong><div class="small text-muted">{{ $review->rating }}/5 • {{ ucfirst($review->status) }}</div><div>{{ $review->title }}</div></div>@empty @include('user.pages.partials.panel-empty', ['message' => 'No reviews found yet.', 'url' => route('user.tickets.index'), 'label' => 'View Tickets']) @endforelse</div>
    </div></div></div>
</x-frontend-app-layout>
