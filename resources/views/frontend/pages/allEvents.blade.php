<x-frontend-app-layout :title="'Explore Events'" :seo-meta="$seoMeta ?? []">
    <div class="hero-banner">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-lg-10 col-md-12">
                    <div class="hero-banner-content">
                        <h2>Discover events for all the things you love</h2>
                        <p class="text-white-50 mb-4">Search public, published marketplace events by category, date, city, format, and price.</p>

                        <form class="search-form main-form" id="event-filter-form" method="GET" action="{{ route('all.events') }}">
                            <div class="row g-3">
                                <div class="col-lg-4 col-md-12">
                                    <div class="form-group search-input">
                                        <input type="text" name="search" value="{{ request('search') }}" class="form-control h_50" placeholder="Search event, organizer, venue, city..." id="event-search-input" />
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="form-group">
                                        <select class="form-select h_50" name="event_type_id" id="event-type-select">
                                            <option value="0">All Categories</option>
                                            @foreach ($event_types as $event_type)
                                                <option value="{{ $event_type->id }}" @selected((string) request('event_type_id') === (string) $event_type->id)>{{ $event_type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="form-group">
                                        <select class="form-select h_50" name="city" id="event-city-select">
                                            <option value="">All Cities</option>
                                            @foreach (($cities ?? collect()) as $city)
                                                <option value="{{ $city }}" @selected(request('city') === $city)>{{ $city }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-12">
                                    <button type="submit" class="main-btn btn-hover w-100">Find</button>
                                </div>
                            </div>

                            <div class="row g-3 mt-2">
                                <div class="col-lg-3 col-md-6">
                                    <select class="form-select h_50" name="date_filter" id="event-date-filter">
                                        <option value="all" @selected(request('date_filter', 'all') === 'all')>Any Date</option>
                                        <option value="today" @selected(request('date_filter') === 'today')>Today</option>
                                        <option value="tomorrow" @selected(request('date_filter') === 'tomorrow')>Tomorrow</option>
                                        <option value="this_week" @selected(request('date_filter') === 'this_week')>This Week</option>
                                        <option value="this_month" @selected(request('date_filter') === 'this_month')>This Month</option>
                                        <option value="upcoming" @selected(request('date_filter') === 'upcoming')>Upcoming</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <select class="form-select h_50" name="price" id="event-price-filter">
                                        <option value="all" @selected(request('price', 'all') === 'all')>Any Price</option>
                                        <option value="free" @selected(request('price') === 'free')>Free</option>
                                        <option value="paid" @selected(request('price') === 'paid')>Paid</option>
                                        <option value="donation" @selected(request('price') === 'donation')>Donation</option>
                                        <option value="invite_only" @selected(request('price') === 'invite_only')>Invite Only</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <select class="form-select h_50" name="format" id="event-format-filter">
                                        <option value="all" @selected(request('format', 'all') === 'all')>Any Format</option>
                                        <option value="physical" @selected(request('format') === 'physical')>Physical</option>
                                        <option value="online" @selected(request('format') === 'online')>Online</option>
                                        <option value="hybrid" @selected(request('format') === 'hybrid')>Hybrid</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <a href="{{ route('all.events') }}" class="main-btn btn-hover w-100 text-center">Clear Filters</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="explore-events p-80">
        <div class="container">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="event-filter-items">
                        <div class="featured-controls">
                            @if ($event_types->isNotEmpty())
                                <div class="controls mb-4">
                                    <a href="{{ route('all.events', request()->except(['category_slug', 'event_type_id', 'page'])) }}" class="control {{ !request('category_slug') && !request('event_type_id') ? 'active' : '' }}">All</a>
                                    @foreach ($event_types as $type)
                                        <a href="{{ route('all.events', array_merge(request()->except(['page', 'event_type_id']), ['category_slug' => $type->slug])) }}" class="control {{ request('category_slug') === $type->slug ? 'active' : '' }}">
                                            {{ $type->name }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h3 class="mb-0">Available Events</h3>
                                <span class="text-muted">{{ $events->total() }} result{{ $events->total() === 1 ? '' : 's' }}</span>
                            </div>

                            <div class="row" data-ref="event-filter-content">
                                @include('frontend.layouts.event_grid', compact('events'))
                            </div>

                            <div class="mt-4 d-flex justify-content-center">
                                {{ $events->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-frontend-app-layout>
