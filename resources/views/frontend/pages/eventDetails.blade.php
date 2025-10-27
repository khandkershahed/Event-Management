<x-frontend-app-layout>
    <div class="breadcrumb-block">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-10">
                    <div class="barren-breadcrumb">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('homepage') }}">Home</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('all.events') }}">Explore Events</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ $event->name }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="event-dt-block p-80">
        <div class="container">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="event-top-dts">
                        <div class="event-top-date">
                            {{-- DYNAMIC --}}
                            <span class="event-month">{{ $event->start_date?->format('M') ?? 'TBA' }}</span>
                            <span class="event-date">{{ $event->start_date?->format('d') ?? '??' }}</span>
                        </div>
                        <div class="event-top-dt">
                            <h3 class="event-main-title">
                                {{ $event->name }}
                            </h3>
                            <div class="event-top-info-status">
                                {{-- DYNAMIC --}}
                                <span class="event-type-name">
                                    <i class="fa-solid fa-location-dot"></i>
                                    {{ $event->eventType?->name ?? 'Event' }}
                                </span>
                                {{-- DYNAMIC --}}
                                <span class="event-type-name details-hr">Starts on
                                    <span class="ev-event-date">
                                        {{ $event->start_date?->format('D, M d, Y') }}
                                        {{ $event->start_time?->format('g:i A') ?? '' }}
                                    </span>
                                </span>
                                {{-- DYNAMIC (uses the new 'duration' accessor from the model) --}}
                                @if($event->duration)
                                    <span class="event-type-name details-hr">{{ $event->duration }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8 col-lg-7 col-md-12">
                    <div class="main-event-dt">
                        <div class="event-img">
                            {{-- DYNAMIC --}}
                            <img src="{{ asset('storage/' . $event->banner_image) }}" alt="{{ $event->name }}" />
                        </div>
                        <div class="share-save-btns dropdown">
                            <button class="sv-btn me-2">
                                <i class="fa-regular fa-bookmark me-2"></i>Save
                            </button>
                            <button class="sv-btn" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-share-nodes me-2"></i>Share
                            </button>
                            <ul class="dropdown-menu">
                                {{-- These links can be made dynamic with JS if needed, but are fine as is --}}
                                <li>
                                    <a class="dropdown-item" href="#"><i
                                            class="fa-brands fa-facebook me-3"></i>Facebook</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#"><i
                                            class="fa-brands fa-twitter me-3"></i>Twitter</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#"><i
                                            class="fa-brands fa-linkedin-in me-3"></i>LinkedIn</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#"><i
                                            class="fa-regular fa-envelope me-3"></i>Email</a>
                                </li>
                            </ul>
                        </div>
                        <div class="main-event-content">
                            <h4>About This Event</h4>
                            <p>
                                {!! $event->description !!}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-5 col-md-12">
                    <div class="main-card event-right-dt">
                        <div class="bp-title">
                            <h4>Event Details</h4>
                        </div>
                        {{--
                            DYNAMIC COUNTDOWN:
                            Pass the date to your JavaScript. Your JS (not provided) needs to
                            read this 'data-countdown-date' attribute to initialize.
                        --}}
                        <div class="time-left" data-countdown-date="{{ $event->start_date?->format('Y-m-d') }}T{{ $event->start_time?->format('H:i:s') }}">
                            <div class="countdown">
                                <div class="countdown-item"><span id="day"></span>days</div>
                                <div class="countdown-item">
                                    <span id="hour"></span>Hours
                                </div>
                                <div class="countdown-item">
                                    <span id="minute"></span>Minutes
                                </div>
                                <div class="countdown-item">
                                    <span id="second"></span>Seconds
                                </div>
                            </div>
                        </div>
                        <div class="event-dt-right-group mt-5">
                            <div class="event-dt-right-icon">
                                <i class="fa-solid fa-circle-user"></i>
                            </div>
                            <div class="event-dt-right-content">
                                <h4>Organised by</h4>
                                {{-- DYNAMIC --}}
                                <h5>{{ $event->organizer_name ?? 'The Organizer' }}</h5>
                                @if($event->organizer_brand)
                                    <small class="text-muted">{{ $event->organizer_brand }}</small>
                                @endif
                                {{-- <a href="attendee_profile_view.html">View Profile</a> --}}
                            </div>
                        </div>
                        <div class="event-dt-right-group">
                            <div class="event-dt-right-icon">
                                <i class="fa-solid fa-calendar-day"></i>
                            </div>
                            <div class="event-dt-right-content">
                                <h4>Date and Time</h4>
                                {{-- DYNAMIC --}}
                                <h5>
                                    {{ $event->start_date?->format('D, M d, Y') }}
                                    {{ $event->start_time?->format('g:i A') ?? '' }}
                                </h5>
                                <div class="add-to-calendar">
                                    <a href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-regular fa-calendar-days me-3"></i>Add to
                                        Calendar
                                    </a>
                                    {{-- These links can be made dynamic, but require .ics file generation --}}
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="#"><i
                                                    class="fa-brands fa-windows me-3"></i>Outlook</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#"><i
                                                    class="fa-brands fa-apple me-3"></i>Apple</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#"><i
                                                    class="fa-brands fa-google me-3"></i>Google</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#"><i
                                                    class="fa-brands fa-yahoo me-3"></i>Yahoo</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="event-dt-right-group">
                            <div class="event-dt-right-icon">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div class="event-dt-right-content">
                                <h4>Location</h4>
                                {{-- DYNAMIC --}}
                                <h5 class="mb-0">
                                    {{ $event->venue ?? 'Location to be announced' }}
                                </h5>
                                {{-- DYNAMIC --}}
                                <a href="{{ $event->location_map_url ?? '#' }}"
                                   @if($event->location_map_url) target="_blank" @endif>
                                   <i class="fa-solid fa-location-dot me-2"></i>View Map
                                </a>
                            </div>
                        </div>
                        <div class="select-tickets-block">
                            <h6>Select Tickets</h6>
                            {{--
                                DYNAMIC (ASSUMPTION):
                                This part needs a separate 'tickets' relationship.
                                I'm using the 'display_price' accessor you had in your 'More Events' loop.
                            --}}
                            <div class="select-ticket-action">
                                <div class="ticket-price">{{ $event->display_price ?? 'N/A' }}</div>
                                <div class="quantity">
                                    <div class="counter">
                                        <span class="down" onClick="decreaseCount(event, this)">-</span>
                                        <input type="text" value="0" />
                                        <span class="up" onClick="increaseCount(event, this)">+</span>
                                    </div>
                                </div>
                            </div>
                            {{-- DYNAMIC: This text should come from ticket info. Placeholder for now. --}}
                            <p>
                                {{ $event->ticket_description_placeholder ?? 'Ticket details and inclusions.' }}
                            </p>
                            <div class="xtotel-tickets-count">
                                <div class="x-title">1x Ticket(s)</div>
                                <h4>AUD <span>$0.00</span></h4>
                            </div>
                        </div>
                        <div class="booking-btn">
                            {{-- DYNAMIC (ASSUMPTION): Assumes you have a checkout route. --}}
                            <a href="{{ route('checkout', $event->slug) }}" class="main-btn btn-hover w-100">Book Now</a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="more-events">
                        <div class="main-title position-relative">
                            <h3>More Events</h3>
                            <a href="{{ route('all.events') }}" class="view-all-link">Browse All<i
                                    class="fa-solid fa-right-long ms-2"></i></a>
                        </div>

                        {{--
                            DYNAMIC (FROM YOUR CODE):
                            This part was already dynamic in your request.
                            It will now work because the controller provides $relatedEvents.
                        --}}
                        <div class="owl-carousel moreEvents-slider owl-theme">
                            @forelse ($relatedEvents as $relatedEvent)
                                <div class="item">
                                    <div class="main-card mt-4">
                                        <div class="event-thumbnail">
                                            <a href="{{ route('event.details', $relatedEvent->slug) }}" class="thumbnail-img">
                                                <img src="{{ asset('storage/' . $relatedEvent->image) }}"
                                                    alt="{{ $relatedEvent->name }}" />
                                            </a>
                                            <span class="bookmark-icon" title="Bookmark"></span>
                                        </div>
                                        <div class="event-content">
                                            <a href="{{ route('event.details', $relatedEvent->slug) }}" class="event-title">
                                                {{ $relatedEvent->name }}
                                            </a>
                                            <div class="duration-price-remaining">
                                                <span
                                                    class="duration-price">{{ $relatedEvent->display_price ?? 'Check Price' }}</span>

                                                @if (isset($relatedEvent->tickets_remaining) && $relatedEvent->tickets_remaining > 0)
                                                    <span class="remaining">
                                                        <i class="fa-solid fa-ticket fa-rotate-90"></i>
                                                        {{ $relatedEvent->tickets_remaining }} Remaining
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="event-footer">
                                            <div class="event-timing">
                                                <div class="publish-date">
                                                    <span>
                                                        <i class="fa-solid fa-calendar-day me-2"></i>
                                                        {{ $relatedEvent->start_date?->format('d M') ?? 'TBA' }}
                                                    </span>
                                                    <span class="dot"><i class="fa-solid fa-circle"></i></span>
                                                    <span>
                                                        {{ $relatedEvent->start_date?->format('D') }},
                                                        {{ $relatedEvent->start_time?->format('g:i A') ?? 'Time TBA' }}
                                                    </span>
                                                </div>

                                                @if ($relatedEvent->duration)
                                                    <span class="publish-time">
                                                        <i class="fa-solid fa-clock me-2"></i>
                                                        {{ $relatedEvent->duration }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="item">
                                    <p>No other events in this category.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-frontend-app-layout>
