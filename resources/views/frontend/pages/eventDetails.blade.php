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
        Event Details Page
    ============================ --}}
    <div class="event-dt-block p-80">
        <div class="container">
            <div class="row">

                {{-- ===========================
                    Title + Date
                ============================ --}}
                <div class="col-xl-12">
                    <div class="event-top-dts">

                        <div class="event-top-date">
                            <span class="event-month">{{ $event->start_date?->format('M') ?? 'TBA' }}</span>
                            <span class="event-date">{{ $event->start_date?->format('d') ?? '??' }}</span>
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

                                @if ($event->duration)
                                    <span class="event-type-name details-hr">{{ $event->duration }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===========================
                    LEFT SIDE
                ============================ --}}
                <div class="col-xl-8 col-lg-7">

                    {{-- Event Image --}}
                    <div class="main-event-dt">
                        <div class="event-img">
                            <img src="{{ asset('storage/' . $event->banner_image) }}"
                                 alt="{{ $event->name }}"
                                 onerror="this.src='https://media.istockphoto.com/id/1055079680/vector/black-linear-photo-camera-like-no-image-available.jpg?s=612x612';" />
                        </div>

                        {{-- Share Buttons --}}
                        <div class="share-save-btns dropdown">
                            <button class="sv-btn me-2"><i class="fa-regular fa-bookmark me-2"></i>Save</button>
                            <button class="sv-btn" data-bs-toggle="dropdown"><i class="fa-solid fa-share-nodes me-2"></i>Share</button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"><i class="fa-brands fa-facebook me-3"></i>Facebook</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fa-brands fa-twitter me-3"></i>Twitter</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fa-brands fa-linkedin-in me-3"></i>LinkedIn</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fa-regular fa-envelope me-3"></i>Email</a></li>
                            </ul>
                        </div>

                        {{-- About Event --}}
                        <div class="main-event-content">
                            <h4>About This Event</h4>
                            {!! $event->description !!}
                        </div>
                    </div>

                    {{-- Organizer --}}
                    <div class="mt-5">
                        <h3 class="event-main-title">Organized by</h3>
                        <div class="p-4 bg-white row align-items-center rounded-2">
                            <div class="col-lg-2">
                                <img class="img-fluid"
                                     src="https://img.evbuc.com/https%3A%2F%2Fcdn.evbuc.com%2Fimages%2F959246063%2F2589230919051%2F1%2Foriginal.20250212-182015?w=512"
                                     alt="">
                            </div>

                            <div class="col-lg-6">
                                <h4 class="fw-bold">Voice Coaches</h4>
                                <div class="row mt-3">
                                    <div class="col-lg-4"><h4 class="mb-0 x-title">Followers</h4><p>1.4k</p></div>
                                    <div class="col-lg-4"><h4 class="mb-0 x-title">Events</h4><p>81</p></div>
                                    <div class="col-lg-4"><h4 class="mb-0 x-title">Hosting</h4><p>9 months</p></div>
                                </div>
                            </div>

                            <div class="col-lg-4 d-flex justify-content-center align-items-center">
                                <a href="" class="main-btn btn-hover w-100 me-3">Contact</a>
                                <a href="" class="main-btn btn-hover w-100">Follow</a>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- ===========================
                    RIGHT SIDE
                ============================ --}}
                <div class="col-xl-4 col-lg-5">

                    <div class="main-card event-right-dt">

                        {{-- Event Details --}}
                        <div class="bp-title"><h4>Event Details</h4></div>

                        {{-- Countdown --}}
                        <div class="time-left"
                             data-countdown-date="{{ $event->start_date?->format('Y-m-d') }} {{ $event->start_time?->format('H:i:s') }}">
                            <div class="countdown">
                                <div class="countdown-item"><span id="day"></span> days</div>
                                <div class="countdown-item"><span id="hour"></span> Hours</div>
                                <div class="countdown-item"><span id="minute"></span> Minutes</div>
                                <div class="countdown-item"><span id="second"></span> Seconds</div>
                            </div>
                        </div>

                        {{-- Organizer --}}
                        <div class="mt-5 event-dt-right-group">
                            <div class="event-dt-right-icon"><i class="fa-solid fa-circle-user"></i></div>
                            <div class="event-dt-right-content">
                                <h4>Organised by</h4>
                                <h5>{{ $event->organizer_name ?? 'The Organizer' }}</h5>
                                @if ($event->organizer_brand)
                                    <small class="text-muted">{{ $event->organizer_brand }}</small>
                                @endif
                            </div>
                        </div>

                        {{-- Date --}}
                        <div class="event-dt-right-group">
                            <div class="event-dt-right-icon"><i class="fa-solid fa-calendar-day"></i></div>
                            <div class="event-dt-right-content">
                                <h4>Date and Time</h4>
                                <h5>{{ $event->start_date?->format('D, M d, Y') }} {{ $event->start_time?->format('g:i A') }}</h5>
                            </div>
                        </div>

                        {{-- Venue --}}
                        <div class="event-dt-right-group">
                            <div class="event-dt-right-icon"><i class="fa-solid fa-location-dot"></i></div>
                            <div class="event-dt-right-content">
                                <h4>Location</h4>
                                <h5>{{ $event->venue ?? 'TBA' }}</h5>
                                <a href="{{ $event->location_map_url ?? '#' }}" @if($event->location_map_url) target="_blank" @endif>
                                    <i class="fa-solid fa-location-dot me-2"></i>View Map
                                </a>
                            </div>
                        </div>

                        {{-- Book --}}
                        <div class="booking-btn mt-4">
                            <button type="button"
                                    class="main-btn btn-hover w-100"
                                    id="openSeatSelectorButton"
                                    data-bs-toggle="modal"
                                    data-bs-target="#seatSelectorModal">
                                Get Ticket
                            </button>
                        </div>
                    </div>

                </div>

                {{-- MORE EVENTS --}}
                <div class="col-xl-12 mt-5">
                    <div class="more-events">
                        <div class="main-title position-relative">
                            <h3>More Events</h3>
                            <a href="{{ route('all.events') }}" class="view-all-link">Browse All<i class="fa-solid fa-right-long ms-2"></i></a>
                        </div>

                        <div class="owl-carousel moreEvents-slider owl-theme">
                            @forelse ($relatedEvents as $relatedEvent)
                                <div class="item">
                                    <div class="mt-4 main-card">
                                        <div class="event-thumbnail">
                                            <a href="{{ route('event.details', $relatedEvent->slug) }}" class="thumbnail-img">
                                                <img src="{{ asset('storage/' . $relatedEvent->image) }}" alt="">
                                            </a>
                                        </div>
                                        <div class="event-content">
                                            <a href="{{ route('event.details', $relatedEvent->slug) }}" class="event-title">{{ $relatedEvent->name }}</a>
                                            <div class="duration-price-remaining">
                                                <span class="duration-price">{{ $relatedEvent->display_price ?? 'Check Price' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p>No related events</p>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Include the unified modal --}}
    @include("frontend.pages.partial.SeatModal")

    {{-- ===========================
        Countdown + Polling JS
    ============================ --}}
    @push('scripts')

    {{-- Countdown --}}
    <script>
        function startCountdown() {
            const el = document.querySelector('.time-left');
            const date = new Date(el.dataset.countdownDate.replace(" ", "T")).getTime();

            const d = document.getElementById("day");
            const h = document.getElementById("hour");
            const m = document.getElementById("minute");
            const s = document.getElementById("second");

            setInterval(() => {
                let now = new Date().getTime();
                let diff = date - now;

                if (diff <= 0) { d.textContent = h.textContent = m.textContent = s.textContent = 0; return; }

                d.textContent = Math.floor(diff / (1000 * 60 * 60 * 24));
                h.textContent = Math.floor((diff % (86400000)) / 3600000);
                m.textContent = Math.floor((diff % 3600000) / 60000);
                s.textContent = Math.floor((diff % 60000) / 1000);
            }, 1000);
        }

        startCountdown();
    </script>

    {{-- Live Seat Polling --}}
    <script>
        const POLL_URL = "{{ route('frontend.seat.availability', $event->id) }}";
        const POLL_INTERVAL = 5000;
        window.seatStatusMap = @json($seatStatuses);
        window.userSelectedSeats = new Set();

        setInterval(() => {
            fetch(POLL_URL)
            .then(r=>r.json())
            .then(data => {
                if (data.status === "success") {
                    updateSeatStatusUI(data.seats);
                    seatStatusMap = data.seats;
                }
            });
        }, POLL_INTERVAL);

        function updateSeatStatusUI(updated) {
            for (const id in updated) {
                if (userSelectedSeats.has(parseInt(id))) continue;
                let shape = window.SEAT_SHAPES[id];
                if (!shape) continue;

                switch(updated[id]) {
                    case 'available': shape.fill('#2ecc71'); shape.opacity(1); break;
                    case 'locked': shape.fill('#f1c40f'); shape.opacity(0.6); break;
                    case 'sold': shape.fill('#e74c3c'); shape.opacity(1); shape.off('click'); break;
                }
                shape.draw();
            }
        }
    </script>
    @endpush

</x-frontend-app-layout>
