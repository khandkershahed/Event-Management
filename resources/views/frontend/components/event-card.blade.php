@php
    $image = $event->banner_image ?: ($event->image ?: ($event->venue_image ?: null));
    $imageUrl = $image ? asset('storage/' . $image) : asset('images/no_image.jpg');
    $organizerName = $event->organizerProfile?->organization_name ?: ($event->organizer_name ?: 'Organizer TBA');
    $ticketCount = $event->tickets_remaining ?? 0;
    $savedEventIds = $savedEventIds ?? [];
    $isSaved = in_array($event->id, $savedEventIds, true);
    $eventDate = $event->start_date?->format('d M') ?? 'Date TBA';
@endphp

<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mix {{ optional($event->eventType)->slug }} mb-4" data-ref="mixitup-target">
    <article class="main-card mt-4 h-100 d-flex flex-column" aria-labelledby="event-card-title-{{ $event->id }}">
        <div class="event-thumbnail position-relative">
            <a href="{{ route('event.details', $event->slug) }}" class="thumbnail-img"
                aria-label="Open event details for {{ $event->name }}">
                <img src="{{ $imageUrl }}" alt="{{ $event->name }} event cover" loading="lazy"
                    onerror="this.src='{{ asset('images/no_image.jpg') }}';" />
            </a>
            <div class="position-absolute top-0 start-0 m-2">
                <span class="badge bg-dark bg-opacity-75">{{ $eventDate }}</span>
            </div>
            <div class="position-absolute top-0 end-0 m-2">
                @auth
                    @if ($isSaved)
                        <form method="POST" action="{{ route('public.events.unsave', $event) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-light" title="Remove from saved events"
                                aria-label="Remove {{ $event->name }} from saved events">
                                <i class="fa-solid fa-bookmark text-primary" aria-hidden="true"></i>
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('public.events.save', $event) }}">
                            @csrf
                            <input type="hidden" name="source" value="event_card">
                            <button type="submit" class="btn btn-sm btn-light" title="Save event"
                                aria-label="Save {{ $event->name }}">
                                <i class="fa-regular fa-bookmark" aria-hidden="true"></i>
                            </button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-sm btn-light" title="Login to save"
                        aria-label="Login to save {{ $event->name }}">
                        <i class="fa-regular fa-bookmark" aria-hidden="true"></i>
                    </a>
                @endauth
            </div>
        </div>
        <div class="event-content flex-grow-1">
            <a href="{{ route('event.details', $event->slug) }}" class="event-title"
                id="event-card-title-{{ $event->id }}">
                {{ $event->name }}
            </a>
            <p class="mb-2 text-muted small">By {{ $organizerName }}</p>
            <div class="duration-price-remaining">
                <span class="duration-price">{{ $event->display_price ?? 'Ticket TBA' }}</span>
                @if ($ticketCount > 0)
                    <span class="remaining" aria-label="{{ $ticketCount }} tickets remaining">
                        <i class="fa-solid fa-ticket fa-rotate-90" aria-hidden="true"></i>
                        {{ $ticketCount }} Remaining
                    </span>
                @else
                    <span class="remaining text-muted">
                        <i class="fa-solid fa-ticket fa-rotate-90" aria-hidden="true"></i>
                        Sales TBA
                    </span>
                @endif
            </div>
        </div>
        <div class="event-footer mt-auto">
            <div class="event-timing">
                <div class="publish-date">
                    <span>
                        <i class="fa-solid fa-calendar-day me-2" aria-hidden="true"></i>
                        {{ $event->start_date?->format('d M') ?? 'Date TBA' }}
                    </span>
                    <span class="dot" aria-hidden="true"><i class="fa-solid fa-circle"></i></span>
                    <span>
                        {{ $event->start_date?->format('D') ?? 'Day TBA' }},
                        {{ $event->start_time?->format('g:i A') ?? 'Time TBA' }}
                    </span>
                </div>
                <span class="publish-time">
                    <i class="fa-solid fa-location-dot me-2" aria-hidden="true"></i>
                    {{ $event->location_label }}
                </span>
                <span class="publish-time d-block mt-1">
                    <i class="fa-solid fa-circle-info me-2" aria-hidden="true"></i>
                    {{ $event->event_format_label }}
                </span>
            </div>
        </div>
    </article>
</div>
