<x-frontend-app-layout :title="'My Offers'">
    @include('user.layout.sidebar')
    <div class="wrapper wrapper-body"><div class="dashboard-body"><div class="container-fluid">
        <div class="d-main-title mb-4"><h3><i class="fa-solid fa-rectangle-ad me-3"></i>My Offers</h3><p class="text-muted mb-0">No static coupons are shown. Available offers are derived from real public ticket/event records.</p></div>
        <div class="row">
            @include('user.pages.partials.panel-stat', ['label' => 'Saved Events', 'value' => $summary['saved_events'] ?? 0, 'icon' => 'fa-bookmark'])
            @include('user.pages.partials.panel-stat', ['label' => 'Followed Organizers', 'value' => $summary['followed_organizers'] ?? 0, 'icon' => 'fa-user-check'])
            @include('user.pages.partials.panel-stat', ['label' => 'Orders', 'value' => $summary['orders'] ?? 0, 'icon' => 'fa-receipt'])
            @include('user.pages.partials.panel-stat', ['label' => 'Tickets', 'value' => $summary['tickets'] ?? 0, 'icon' => 'fa-ticket'])
        </div>
        <div class="main-card p-4">
            <h5 class="mb-3">Recommended Actions</h5>
            <div class="row">
                <div class="col-md-4 mb-3"><a href="{{ route('user.discovery.index') }}" class="btn btn-outline-primary w-100">Personalized Discovery</a></div>
                <div class="col-md-4 mb-3"><a href="{{ route('user.saved-events.index') }}" class="btn btn-outline-primary w-100">Saved Events</a></div>
                <div class="col-md-4 mb-3"><a href="{{ route('all.events') }}" class="btn btn-primary w-100">Browse Events</a></div>
            </div>
        </div>
    </div></div></div>
</x-frontend-app-layout>
