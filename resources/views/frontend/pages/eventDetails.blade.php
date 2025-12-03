<x-frontend-app-layout>

    {{-- ===========================
         Breadcrumb
    ============================ --}}
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

    {{-- ===========================
          Event Details BLOCK
    ============================ --}}
    <div class="event-dt-block p-80">
        <div class="container">
            <div class="row">

                {{-- ===========================
                        TITLE + DATE
                ============================ --}}
                <div class="col-xl-12">
                    <div class="event-top-dts">

                        <div class="event-top-date">
                            <span class="event-month">{{ $event->start_date?->format('M') ?? '?' }}</span>
                            <span class="event-date">{{ $event->start_date?->format('d') ?? '?' }}</span>
                        </div>

                        <div class="event-top-dt">
                            <h3 class="event-main-title">{{ $event->name }}</h3>

                            <div class="event-top-info-status">
                                <span class="event-type-name">
                                    <i class="fa-solid fa-location-dot"></i>
                                    {{ $event->eventType?->name ?? 'Event' }}
                                </span>

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

                {{-- ===========================
                        LEFT CONTENT
                ============================ --}}
                <div class="col-xl-8 col-lg-7">

                    {{-- EVENT BANNER --}}
                    <div class="main-event-dt">
                        <div class="event-img">
                            <img src="{{ asset('storage/' . $event->banner_image) }}"
                                 alt="{{ $event->name }}"
                                 onerror="this.src='{{ asset('images/no_image.png') }}';">
                        </div>

                        {{-- SHARE BUTTONS --}}
                        <div class="share-save-btns dropdown">
                            <button class="sv-btn me-2"><i class="fa-regular fa-bookmark me-2"></i>Save</button>

                            <button class="sv-btn" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-share-nodes me-2"></i>Share
                            </button>

                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Facebook</a></li>
                                <li><a class="dropdown-item" href="#">Twitter</a></li>
                                <li><a class="dropdown-item" href="#">LinkedIn</a></li>
                                <li><a class="dropdown-item" href="#">Email</a></li>
                            </ul>
                        </div>

                        {{-- ABOUT EVENT --}}
                        <div class="main-event-content">
                            <h4>About This Event</h4>
                            {!! $event->description !!}
                        </div>

                    </div>

                    {{-- ORGANIZER --}}
                    <div class="mt-5">
                        <h3 class="event-main-title">Organized by</h3>
                        <div class="p-4 bg-white row align-items-center rounded-2">

                            <div class="col-lg-2">
                                <img class="img-fluid rounded-circle"
                                     src="https://via.placeholder.com/150"
                                     alt="">
                            </div>

                            <div class="col-lg-6">
                                <h4 class="fw-bold">{{ $event->organizer_name ?? 'Organizer' }}</h4>
                            </div>

                            <div class="col-lg-4 text-end">
                                <a href="#" class="main-btn btn-hover w-100">Contact</a>
                            </div>

                        </div>
                    </div>

                </div>

                {{-- ===========================
                        RIGHT SIDEBAR
                ============================ --}}
                <div class="col-xl-4 col-lg-5">
                    <div class="main-card event-right-dt">

                        {{-- EVENT DETAILS --}}
                        <div class="bp-title"><h4>Event Details</h4></div>

                        {{-- COUNTDOWN --}}
                        <div class="time-left"
                             data-countdown-date="{{ $event->start_date?->format('Y-m-d') }} {{ $event->start_time?->format('H:i:s') }}">

                            <div class="countdown">
                                <div class="countdown-item"><span id="day"></span> Days</div>
                                <div class="countdown-item"><span id="hour"></span> Hours</div>
                                <div class="countdown-item"><span id="minute"></span> Min</div>
                                <div class="countdown-item"><span id="second"></span> Sec</div>
                            </div>
                        </div>

                        {{-- VENUE --}}
                        <div class="event-dt-right-group">
                            <div class="event-dt-right-icon">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div class="event-dt-right-content">
                                <h4>Location</h4>
                                <h5>{{ $event->venue?->name ?? 'Venue TBA' }}</h5>
                            </div>
                        </div>

                        {{-- BUTTON: OPEN SEAT SELECTOR --}}
                        <div class="booking-btn mt-4">
                            <button type="button"
                                class="main-btn btn-hover w-100"
                                id="openSeatSelectorButton"
                                data-bs-toggle="modal"
                                data-bs-target="#seatSelectorModal">
                                Get Tickets
                            </button>
                        </div>
                    </div>
                </div>

                {{-- ===========================
                        MORE EVENTS
                ============================ --}}
                <div class="col-xl-12 mt-5">
                    <div class="more-events">
                        <div class="main-title position-relative">
                            <h3>More Events</h3>
                        </div>

                        <div class="owl-carousel moreEvents-slider owl-theme">

                            @foreach ($relatedEvents as $ev)
                                <div class="item">
                                    <div class="mt-4 main-card">
                                        <div class="event-thumbnail">
                                            <a href="{{ route('event.details', $ev->slug) }}" class="thumbnail-img">
                                                <img src="{{ asset('storage/' . $ev->image) }}" alt="">
                                            </a>
                                        </div>
                                        <div class="event-content">
                                            <a href="{{ route('event.details', $ev->slug) }}" class="event-title">
                                                {{ $ev->name }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    {{-- =====================================================
            SEAT SELECTOR MODAL (NEW ENGINE — NO KONVA)
    ====================================================== --}}
    @include("frontend.pages.partial.SeatModal")
    {{-- This file will be provided after EVENT-B Javascript --}}

</x-frontend-app-layout>
