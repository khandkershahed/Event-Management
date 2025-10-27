
@forelse($events as $event)
    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
        <div class="main-card mt-4">
            <div class="event-thumbnail">
                <a href="{{ route('event.details', $event->slug) }}" class="thumbnail-img">
                    <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->name }}" />
                </a>
                <span class="bookmark-icon" title="Bookmark"></span>
            </div>
            <div class="event-content">
                <a href="{{ route('event.details', $event->slug) }}" class="event-title">
                    {{ $event->name }}
                </a>
                <div class="duration-price-remaining">
                    <span class="duration-price">{{ $event->display_price ?? 'Check Price' }}</span>
                    @if (isset($event->tickets_remaining) && $event->tickets_remaining > 0)
                        <span class="remaining">
                            <i class="fa-solid fa-ticket fa-rotate-90"></i>
                            {{ $event->tickets_remaining }} Remaining
                        </span>
                    @endif
                </div>
            </div>
            <div class="event-footer">
                <div class="event-timing">
                    <div class="publish-date">
                        <span>
                            <i class="fa-solid fa-calendar-day me-2"></i>
                            {{ $event->start_date?->format('d M') ?? 'TBA' }}
                        </span>
                        <span class="dot"><i class="fa-solid fa-circle"></i></span>
                        <span>
                            {{ $event->start_date?->format('D') }},
                            {{ $event->start_time?->format('g:i A') ?? 'Time TBA' }}
                        </span>
                    </div>
                    @if ($event->duration)
                        <span class="publish-time">
                            <i class="fa-solid fa-clock me-2"></i>
                            {{ $event->duration }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="col-12 text-center mt-5">
        <h4>No events found.</h4>
        <p>Try adjusting your filters.</p>
    </div>
@endforelse
