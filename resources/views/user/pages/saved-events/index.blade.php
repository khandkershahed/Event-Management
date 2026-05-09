<x-frontend-app-layout :title="'Saved Events'">
    @include('user.layout.sidebar')
    <div class="wrapper wrapper-body">
        <div class="dashboard-body">
            <div class="container-fluid">
                <div class="d-main-title mb-4 d-flex justify-content-between align-items-center">
                    <h3><i class="fa-solid fa-bookmark me-3"></i>Saved Events</h3>
                    <a href="{{ route('user.discovery.index') }}" class="btn btn-primary">Recommended For You</a>
                </div>
                @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                <div class="row">
                    @forelse($savedEvents as $savedEvent)
                        @if($savedEvent->event)
                            @include('frontend.components.event-card', ['event' => $savedEvent->event, 'savedEventIds' => $savedEventIds])
                        @endif
                    @empty
                        <div class="col-12">
                            <div class="main-card p-5 text-center">
                                <h4>No saved events yet.</h4>
                                <p class="text-muted">Save events from public event cards or event detail pages to build your wishlist.</p>
                                <a href="{{ route('all.events') }}" class="main-btn btn-hover">Browse Events</a>
                            </div>
                        </div>
                    @endforelse
                </div>
                <div class="mt-4">{{ $savedEvents->links() }}</div>
            </div>
        </div>
    </div>
</x-frontend-app-layout>
