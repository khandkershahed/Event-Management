<x-frontend-app-layout :title="'Followed Organizers'">
    @include('user.layout.sidebar')
    <div class="wrapper wrapper-body">
        <div class="dashboard-body">
            <div class="container-fluid">
                <div class="d-main-title mb-4 d-flex justify-content-between align-items-center">
                    <h3><i class="fa-solid fa-user-check me-3"></i>Followed Organizers</h3>
                    <a href="{{ route('all.events') }}" class="btn btn-primary">Discover Events</a>
                </div>
                @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
                <div class="row">
                    @forelse($followedOrganizers as $follow)
                        @php($organizer = $follow->organizerProfile)
                        <div class="col-md-6 col-xl-4 mb-4">
                            <div class="main-card p-4 h-100">
                                <h4 class="fw-bold">{{ $organizer?->organization_name }}</h4>
                                <p class="text-muted">{{ str($organizer?->description ?: 'No description.')->limit(120) }}</p>
                                <p class="mb-1">Rating: {{ number_format((float) ($organizer?->ratingSummary?->average_rating ?? 0), 2) }}/5</p>
                                <p class="mb-3">Reviews: {{ (int) ($organizer?->ratingSummary?->approved_reviews_count ?? 0) }}</p>
                                <div class="mb-3">
                                    @foreach($organizer?->publicTrustBadges ?? [] as $badge)
                                        <span class="badge bg-success mb-1">{{ $badge->label }}</span>
                                    @endforeach
                                </div>
                                @if($organizer)
                                    <a href="{{ route('public.organizers.show', $organizer->slug) }}" class="main-btn btn-hover">View Profile</a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-12"><div class="alert alert-light border">You are not following any organizer yet.</div></div>
                    @endforelse
                </div>
                {{ $followedOrganizers->links() }}
            </div>
        </div>
    </div>
</x-frontend-app-layout>
