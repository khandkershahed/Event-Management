<x-frontend-app-layout :title="'My Information'">
    @include('user.layout.sidebar')
    <div class="wrapper wrapper-body"><div class="dashboard-body"><div class="container-fluid">
        <div class="d-main-title mb-4"><h3><i class="fa-solid fa-circle-info me-3"></i>My Information</h3><p class="text-muted mb-0">Your customer profile and marketplace activity summary.</p></div>
        <div class="row">
            <div class="col-lg-4 mb-4"><div class="main-card p-4 h-100"><h5>Profile</h5><p class="mb-1"><strong>Name:</strong> {{ $user->name }}</p><p class="mb-1"><strong>Email:</strong> {{ $user->email }}</p><p class="mb-1"><strong>Joined:</strong> {{ $user->created_at?->format('d M Y') }}</p><a href="{{ route('profile.edit') }}" class="btn btn-sm btn-primary mt-3">Edit Login Profile</a></div></div>
            <div class="col-lg-8 mb-4"><div class="row">
                @include('user.pages.partials.panel-stat', ['label' => 'Orders', 'value' => $summary['orders'] ?? 0, 'icon' => 'fa-receipt'])
                @include('user.pages.partials.panel-stat', ['label' => 'Tickets', 'value' => $summary['tickets'] ?? 0, 'icon' => 'fa-ticket'])
                @include('user.pages.partials.panel-stat', ['label' => 'Saved Events', 'value' => $summary['saved_events'] ?? 0, 'icon' => 'fa-bookmark'])
                @include('user.pages.partials.panel-stat', ['label' => 'Support', 'value' => $summary['support_tickets'] ?? 0, 'icon' => 'fa-headset'])
            </div></div>
        </div>
    </div></div></div>
</x-frontend-app-layout>
