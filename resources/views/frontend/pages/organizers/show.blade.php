<x-frontend-app-layout :seo-meta="$seoMeta ?? []">
    <div class="breadcrumb-block">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('homepage') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('all.events') }}">Explore Events</a></li>
                <li class="breadcrumb-item active">{{ $organizer->organization_name }}</li>
            </ol>
        </div>
    </div>

    <div class="event-dt-block p-80">
        <div class="container">
            @if (session('success')) <div class="alert alert-success" role="status">{{ session('success') }}</div> @endif
            @if (session('error')) <div class="alert alert-danger" role="alert">{{ session('error') }}</div> @endif

            <div class="main-card mb-4 overflow-hidden">
                @php($banner = $organizer->banner)
                <div style="height:220px;background:#f2f2f2;overflow:hidden;">
                    <img src="{{ $banner ? asset('storage/' . $banner) : asset('images/no_image.png') }}" class="w-100 h-100" style="object-fit:cover;" alt="{{ $organizer->organization_name }} organizer cover image" loading="lazy" onerror="this.src='{{ asset('images/no_image.png') }}';">
                </div>
                <div class="p-4">
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center">
                            <img src="{{ $organizer->logo ? asset('storage/' . $organizer->logo) : asset('images/no_image.png') }}" class="rounded-circle img-fluid" style="width:120px;height:120px;object-fit:cover;" alt="{{ $organizer->organization_name }} organizer logo" loading="lazy" onerror="this.src='{{ asset('images/no_image.png') }}';">
                        </div>
                        <div class="col-md-7">
                            <h1 class="fw-bold mb-2 h2">{{ $organizer->organization_name }}</h1>
                            <p class="text-muted mb-2">{{ $organizer->description ?: 'This organizer has not added a public description yet.' }}</p>
                            <div class="d-flex flex-wrap gap-2">
                                @forelse($organizer->publicTrustBadges as $badge)
                                    <span class="badge bg-success"><i class="fa-solid fa-shield-halved"></i> {{ $badge->label }}</span>
                                @empty
                                    <span class="badge bg-light text-dark border">No public trust badges yet</span>
                                @endforelse
                            </div>
                        </div>
                        <div class="col-md-3 text-md-end mt-3 mt-md-0">
                            @auth
                                @if($isFollowing)
                                    <form method="POST" action="{{ route('public.organizers.unfollow', $organizer) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="main-btn btn-hover" type="submit" aria-label="Unfollow {{ $organizer->organization_name }}">Unfollow Organizer</button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('public.organizers.follow', $organizer) }}">
                                        @csrf
                                        <button class="main-btn btn-hover" type="submit" aria-label="Follow {{ $organizer->organization_name }}">Follow Organizer</button>
                                    </form>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="main-btn btn-hover" aria-label="Login to follow {{ $organizer->organization_name }}">Login to Follow</a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-3 mb-3"><div class="main-card p-4 text-center h-100" aria-label="Average organizer rating"><h3>{{ number_format((float) ($organizer->ratingSummary?->average_rating ?? 0), 2) }}/5</h3><p class="mb-0">Average Rating</p></div></div>
                <div class="col-md-3 mb-3"><div class="main-card p-4 text-center h-100" aria-label="Approved review count"><h3>{{ (int) ($organizer->ratingSummary?->approved_reviews_count ?? 0) }}</h3><p class="mb-0">Approved Reviews</p></div></div>
                <div class="col-md-3 mb-3"><div class="main-card p-4 text-center h-100" aria-label="Published event count"><h3>{{ (int) $organizer->published_events_count }}</h3><p class="mb-0">Published Events</p></div></div>
                <div class="col-md-3 mb-3"><div class="main-card p-4 text-center h-100" aria-label="Follower count"><h3>{{ (int) $organizer->followers_count }}</h3><p class="mb-0">Followers</p></div></div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <h3 class="event-main-title mb-3">Published Events</h3>
                    <div class="row">
                        @forelse($publishedEvents as $event)
                            <div class="col-md-6 mb-4">
                                <div class="main-card p-3 h-100">
                                    <a href="{{ route('event.details', $event->slug) }}" class="fw-bold d-block mb-2">{{ $event->name }}</a>
                                    <p class="mb-1"><i class="fa-regular fa-calendar"></i> {{ $event->start_date?->format('M d, Y') ?? 'Date TBA' }}</p>
                                    <p class="mb-1"><i class="fa-solid fa-location-dot"></i> {{ $event->venueRecord?->city ?? $event->venue ?? 'Location TBA' }}</p>
                                    <p class="mb-0"><i class="fa-solid fa-ticket"></i> {{ $event->display_price }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="col-12"><div class="alert alert-light border" role="status"><strong>No published events right now.</strong><br><span class="text-muted">Follow this organizer to find them again when new events are published.</span></div></div>
                        @endforelse
                    </div>
                    {{ $publishedEvents->links() }}
                </div>
                <div class="col-lg-4">
                    <div class="main-card p-4 mb-4">
                        <h4 class="fw-bold mb-3">Organizer Details</h4>
                        @if($organizer->website)<p><i class="fa-solid fa-globe"></i> <a href="{{ $organizer->website }}" target="_blank" rel="noopener">Website</a></p>@endif
                        @if($organizer->email)<p><i class="fa-solid fa-envelope"></i> {{ $organizer->email }}</p>@endif
                        @if($organizer->phone)<p><i class="fa-solid fa-phone"></i> {{ $organizer->phone }}</p>@endif
                        @if($organizer->address)<p><i class="fa-solid fa-location-dot"></i> {{ $organizer->address }}</p>@endif
                    </div>
                    <div class="main-card p-4">
                        <h4 class="fw-bold mb-3">Recent Approved Reviews</h4>
                        @forelse($recentReviews as $review)
                            <div class="border-bottom pb-3 mb-3">
                                <strong>{{ $review->rating }}/5 — {{ $review->title ?: 'Review' }}</strong>
                                <p class="mb-1">{{ $review->body }}</p>
                                <small class="text-muted">{{ $review->event?->name }} by {{ $review->user?->name ?? 'Customer' }}</small>
                            </div>
                        @empty
                            <p class="text-muted mb-0">No approved reviews yet. Reviews will appear here after customers attend and admins approve them.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-frontend-app-layout>
