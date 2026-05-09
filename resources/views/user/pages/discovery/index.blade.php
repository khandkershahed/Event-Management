<x-frontend-app-layout :title="'Recommended Events'">
    @include('user.layout.sidebar')
    <div class="wrapper wrapper-body">
        <div class="dashboard-body">
            <div class="container-fluid">
                <div class="d-main-title mb-4 d-flex justify-content-between align-items-center">
                    <h3><i class="fa-solid fa-wand-magic-sparkles me-3"></i>Recommended Events</h3>
                    <a href="{{ route('user.saved-events.index') }}" class="btn btn-outline-primary">My Saved Events</a>
                </div>
                <div class="alert alert-light border">
                    Recommendations are based only on your saved events, followed organizers, event categories, and published marketplace events. No third-party tracking or extra cookies are used.
                </div>
                <div class="row">
                    @forelse($recommendedEvents as $event)
                        @include('frontend.components.event-card', ['event' => $event, 'savedEventIds' => $savedEventIds])
                    @empty
                        <div class="col-12">
                            <div class="main-card p-5 text-center">
                                <h4>No recommendations yet.</h4>
                                <p class="text-muted">Save a few events or follow organizers to personalize discovery.</p>
                                <a href="{{ route('all.events') }}" class="main-btn btn-hover">Explore Events</a>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-frontend-app-layout>
