@php($savedEventIds = $savedEventIds ?? [])
@forelse($events as $event)
    @include('frontend.components.event-card', ['event' => $event, 'savedEventIds' => $savedEventIds])
@empty
    <div class="col-12 text-center mt-5">
        <div class="main-card p-5">
            <h4>No events found.</h4>
            <p class="text-muted mb-3">Try changing your search keyword, date, price, city, or category filter.</p>
            <a href="{{ route('all.events') }}" class="main-btn btn-hover">Clear Filters</a>
        </div>
    </div>
@endforelse
