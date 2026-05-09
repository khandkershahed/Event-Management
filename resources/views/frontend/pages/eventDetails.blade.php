<x-frontend-app-layout :seo-meta="$seoMeta ?? []">
    <div class="breadcrumb-block">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-10">
                    <div class="barren-breadcrumb">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('homepage') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('all.events') }}">Explore Events</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $event->name }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="event-dt-block p-80">
        <div class="container">
            @if (session('success'))
                <div class="alert alert-success" role="status">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
            @endif
            @if (session('info'))
                <div class="alert alert-info" role="status">{{ session('info') }}</div>
            @endif

            <div class="row">
                <div class="col-xl-12">
                    <div class="event-top-dts">
                        <div class="event-top-date">
                            <span class="event-month">{{ $event->start_date?->format('M') ?? '?' }}</span>
                            <span class="event-date">{{ $event->start_date?->format('d') ?? '?' }}</span>
                        </div>
                        <div class="event-top-dt">
                            <div class="d-flex justify-content-between align-items-start gap-3">
                                <h1 class="event-main-title h3">{{ $event->name }}</h1>
                                <div>
                                    @auth
                                        @if($isSaved ?? false)
                                            <form method="POST" action="{{ route('public.events.unsave', $event) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-primary btn-sm" aria-label="Remove {{ $event->name }} from saved events"><i class="fa-solid fa-bookmark me-1" aria-hidden="true"></i> Saved</button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('public.events.save', $event) }}">
                                                @csrf
                                                <input type="hidden" name="source" value="event_detail">
                                                <button type="submit" class="btn btn-outline-primary btn-sm" aria-label="Save {{ $event->name }}"><i class="fa-regular fa-bookmark me-1" aria-hidden="true"></i> Save Event</button>
                                            </form>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm" aria-label="Login to save {{ $event->name }}"><i class="fa-regular fa-bookmark me-1" aria-hidden="true"></i> Login to Save</a>
                                    @endauth
                                </div>
                            </div>
                            <div class="event-top-info-status">
                                <span class="event-type-name"><i class="fa-solid fa-tag"></i> {{ $event->eventType?->name ?? 'Event' }}</span>
                                <span class="event-type-name details-hr">
                                    Starts on
                                    <span class="ev-event-date">
                                        {{ $event->start_date?->format('D, M d, Y') }}
                                        {{ $event->start_time?->format('g:i A') }}
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-8 col-lg-7">
                    <div class="main-event-dt">
                        <div class="event-img">
                            @php
                                $banner = $event->banner_image ?: $event->image ?: $event->venue_image;
                            @endphp
                            <img src="{{ $banner ? asset('storage/' . $banner) : asset('images/no_image.png') }}"
                                alt="{{ $event->name }} event banner"
                                loading="lazy"
                                onerror="this.src='{{ asset('images/no_image.png') }}';">
                        </div>

                        <div class="main-event-content">
                            <h4>About This Event</h4>
                            <div>{!! $event->description ?: '<p>No description has been added yet.</p>' !!}</div>
                        </div>
                    </div>

                    <div class="mt-5">
                        <h3 class="event-main-title">Organized by</h3>
                        <div class="p-4 bg-white row align-items-center rounded-2">
                            <div class="col-lg-2">
                                @php($organizerLogo = $event->organizerProfile?->logo ?: $event->organizer_logo)
                                <img class="img-fluid rounded-circle"
                                    src="{{ $organizerLogo ? asset('storage/' . $organizerLogo) : asset('images/no_image.png') }}"
                                    alt="{{ $event->organizerProfile?->organization_name ?? $event->organizer_name ?? 'Organizer' }} organizer logo"
                                    loading="lazy"
                                    onerror="this.src='{{ asset('images/no_image.png') }}';">
                            </div>
                            <div class="col-lg-10">
                                <h4 class="fw-bold mb-1">{{ $event->organizerProfile?->organization_name ?? $event->organizer_name ?? 'Organizer' }}</h4>
                                @if($event->organizerProfile?->slug)
                                    <a href="{{ route('public.organizers.show', $event->organizerProfile->slug) }}" class="btn btn-sm btn-outline-primary mb-2">View Organizer Profile</a>
                                @endif
                                @if ($event->organizerProfile?->description)
                                    <p class="mb-0">{{ $event->organizerProfile->description }}</p>
                                @endif
                            </div>
                        </div>
                    </div>


                    <div class="mt-5">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="event-main-title mb-0">Reviews & Ratings</h3>
                            @auth
                                <a href="{{ route('user.event-reviews.create', $event) }}" class="main-btn btn-hover">Write a Review</a>
                            @else
                                <a href="{{ route('login') }}" class="main-btn btn-hover">Login to Review</a>
                            @endauth
                        </div>
                        <div class="p-4 bg-white rounded-2 mb-3">
                            <h4 class="fw-bold mb-1">{{ number_format((float) ($event->approved_reviews_avg_rating ?? 0), 2) }}/5</h4>
                            <p class="text-muted mb-0">Based on {{ (int) ($event->approved_reviews_count ?? 0) }} approved review(s).</p>
                        </div>
                        @forelse($event->approvedReviews as $review)
                            <div class="p-4 bg-white rounded-2 mb-3">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ $review->title ?: 'Event Review' }}</strong>
                                    <span>{{ $review->rating }}/5</span>
                                </div>
                                <p class="mb-1">{{ $review->body }}</p>
                                <small class="text-muted">By {{ $review->user?->name ?? 'Customer' }} on {{ $review->created_at?->format('M d, Y') }}</small>
                            </div>
                        @empty
                            <div class="alert alert-light border" role="status"><strong>No approved reviews yet.</strong><br><span class="text-muted">Be the first eligible attendee to share feedback after attending.</span></div>
                        @endforelse
                    </div>

                    @if ($relatedEvents->count())
                        <div class="more-events mt-5">
                            <div class="main-title position-relative"><h3>Related Events</h3></div>
                            <div class="row">
                                @foreach ($relatedEvents as $relatedEvent)
                                    <div class="col-md-6 mb-4">
                                        <div class="main-card p-3 h-100">
                                            <a href="{{ route('event.details', $relatedEvent->slug) }}" class="event-title fw-bold d-block mb-2">
                                                {{ $relatedEvent->name }}
                                            </a>
                                            <p class="mb-1"><i class="fa-regular fa-calendar"></i> {{ $relatedEvent->start_date?->format('M d, Y') ?? 'Date TBA' }}</p>
                                            <p class="mb-0"><i class="fa-solid fa-location-dot"></i> {{ $relatedEvent->venueRecord?->city ?? $relatedEvent->venue ?? 'Location TBA' }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <div class="col-xl-4 col-lg-5">
                    <div class="main-card event-right-dt mb-4">
                        <div class="bp-title"><h4>Event Details</h4></div>

                        <div class="event-dt-right-group">
                            <div class="event-dt-right-icon"><i class="fa-solid fa-location-dot"></i></div>
                            <div class="event-dt-right-content">
                                <h4>Location</h4>
                                <h5>{{ $event->venueRecord?->name ?? $event->venue ?? 'Venue TBA' }}</h5>
                                @if ($event->venueRecord?->address || $event->venueRecord?->city)
                                    <p class="mb-0">{{ $event->venueRecord?->address }} {{ $event->venueRecord?->city }}</p>
                                @endif
                                @if ($event->location_map_url || $event->venueRecord?->map_link)
                                    <a href="{{ $event->location_map_url ?: $event->venueRecord?->map_link }}" target="_blank" rel="noopener">Open map</a>
                                @endif
                            </div>
                        </div>

                        <div class="event-dt-right-group">
                            <div class="event-dt-right-icon"><i class="fa-regular fa-clock"></i></div>
                            <div class="event-dt-right-content">
                                <h4>Date & Time</h4>
                                <h5>{{ $event->start_date?->format('D, M d, Y') ?? 'Date TBA' }}</h5>
                                <p class="mb-0">{{ $event->start_time?->format('g:i A') }} @if($event->end_time) - {{ $event->end_time?->format('g:i A') }} @endif</p>
                            </div>
                        </div>
                    </div>

                    <div class="main-card p-4">
                        <div class="bp-title"><h4>Select Tickets</h4></div>

                        @forelse ($ticketTypes as $ticket)
                            <div class="border rounded p-3 mb-3">
                                <div class="d-flex justify-content-between gap-3">
                                    <div>
                                        <h5 class="mb-1">{{ $ticket->name }}</h5>
                                        <p class="mb-1 text-muted">{{ $ticket->description }}</p>
                                        <small>Remaining: {{ $ticket->remainingQuantity() === PHP_INT_MAX ? 'Available' : $ticket->remainingQuantity() }}</small>
                                    </div>
                                    <strong>{{ $ticket->formattedPrice() }}</strong>
                                </div>

                                @if ($event->seating_plan_id && $ticket->requiresSeatSelection())
                                    <a href="{{ route('frontend.seats.select', ['event' => $event->slug, 'ticket' => $ticket->id]) }}" class="main-btn btn-hover w-100 mt-3" aria-label="Select seats for {{ $ticket->name }}">
                                        Select Seats
                                    </a>
                                @else
                                    <form action="{{ route('frontend.cart.add') }}" method="POST" class="mt-3">
                                        @csrf
                                        <input type="hidden" name="event_ticket_id" value="{{ $ticket->id }}">
                                        <div class="d-flex align-items-center gap-2">
                                            <input type="number" name="quantity" value="{{ max(1, (int) $ticket->min_per_order) }}" min="{{ max(1, (int) $ticket->min_per_order) }}" max="{{ $ticket->max_per_order ?: $ticket->remainingQuantity() }}" class="form-control" style="max-width: 120px;" aria-label="Ticket quantity for {{ $ticket->name }}">
                                            <button type="submit" class="main-btn btn-hover flex-fill" aria-label="Add {{ $ticket->name }} to cart">Add to Cart</button>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        @empty
                            <div class="alert alert-warning mb-0" role="status"><strong>No public tickets are currently on sale.</strong><br><span class="small">Please check back later or follow the organizer for future updates.</span></div>
                        @endforelse

                        <div class="alert alert-info mt-3 mb-0" role="note">
                            Seats and ticket quantities are reserved only after they are added to the cart and checkout is completed.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-frontend-app-layout>
