<x-frontend-app-layout :title="'All Events'">
    <div class="hero-banner">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-8 col-md-10">
                    <div class="hero-banner-content">
                        <h2>Discover Events For All The Things You Love</h2>

                        {{-- Wrap in a form for easy access --}}
                        <form class="search-form main-form" id="event-filter-form">
                            <div class="row g-3">
                                <div class="col-lg-5 col-md-12">
                                    <div class="form-group">
                                        {{-- Add an ID for jQuery --}}
                                        <select class="selectpicker" data-width="100%" data-size="5"
                                            data-live-search="true" id="event-type-select">
                                            <option value="0" selected>All Types</option>
                                            @foreach ($event_types as $event_type)
                                                <option value="{{ $event_type->id }}">{{ $event_type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-5 col-md-12">
                                    <div class="form-group search-input">
                                        {{-- Add an ID for jQuery --}}
                                        <input type="text" class="form-control h_50" placeholder="Search events..."
                                            id="event-search-input" />
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-12">
                                    {{-- Change to a button type="submit" to also allow pressing Enter --}}
                                    <button type="submit" class="main-btn btn-hover w-100">Find</button>
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
                            <div class="filter-tag">
                                {{-- Add class and data-values for jQuery --}}
                                <a href="#" class="date-filter active" data-value="all">All</a>
                                <a href="#" class="date-filter" data-value="today">Today</a>
                                <a href="#" class="date-filter" data-value="tomorrow">Tomorrow</a>
                                <a href="#" class="date-filter" data-value="this_week">This Week</a>
                                <a href="#" class="date-filter" data-value="this_weekend">This Weekend</a>
                                <a href="#" class="date-filter" data-value="next_week">Next Week</a>
                                <a href="#" class="date-filter" data-value="this_month">This Month</a>
                                {{-- Add the rest of your date filters here --}}
                            </div>

                            @if ($event_types->isNotEmpty())
                                <div class="controls">
                                    <button type="button" class="control category-filter active"
                                        data-value="all">All</button>
                                    @foreach ($event_types as $type)
                                        <button type="button" class="control category-filter"
                                            data-value="{{ $type->slug }}">
                                            {{ $type->name }}
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                            <div class="row" id="event-grid-container">
                                {{-- Load the initial events from the controller --}}
                                @include('frontend.layouts.event_grid', compact('events'))
                            </div>
                            <div id="loading-spinner" class="text-center p-5" style="display: none;">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>

                            <div class="browse-btn">
                                <button class="main-btn btn-hover" id="see-more-btn" data-page="1"
                                    style="{{ $events->hasMorePages() ? '' : 'display: none;' }}">
                                    See More
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Push the jQuery script to your layout's 'scripts' stack --}}
    @push('scripts')
        <script>
            $(document).ready(function() {
                // --- CSRF Token Setup ---
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                // --- The Main AJAX Function ---
                function fetchEvents(page, append = false) {
                    let search = $('#event-search-input').val() || '';
                    let event_type_id = $('#event-type-select').val() || '0';
                    let date_filter = $('.date-filter.active').data('value') || 'all';
                    let category_slug = $('.category-filter.active').data('value') || 'all';

                    $('#loading-spinner').show();
                    $('#see-more-btn').hide();

                    $.ajax({
                        url: "{{ route('events.fetch') }}",
                        type: 'GET',
                        data: {
                            page: page,
                            search: search,
                            event_type_id: event_type_id,
                            date_filter: date_filter,
                            category_slug: category_slug
                        },
                        success: function(response) {
                            if (append) {
                                $('#event-grid-container').append(response.html);
                            } else {
                                $('#event-grid-container').html(response.html);
                            }

                            // Update pagination
                            $('#see-more-btn').data('page', page);

                            if (response.hasMorePages) {
                                $('#see-more-btn').show();
                            } else {
                                $('#see-more-btn').hide();
                            }
                        },
                        error: function(xhr) {
                            console.error("Error: ", xhr.responseText);
                        },
                        complete: function() {
                            $('#loading-spinner').hide();
                        }
                    });
                }


                // --- Event Handlers ---

                // 1. Filter Form Submission (Search + Dropdown)
                $('#event-filter-form').on('submit', function(e) {
                    e.preventDefault(); // Stop form from submitting normally
                    fetchEvents(1, false); // Fetch page 1, replace content
                });

                // 2. Date Filter Clicks
                $('.date-filter').on('click', function(e) {
                    e.preventDefault();
                    $('.date-filter').removeClass('active');
                    $(this).addClass('active');
                    fetchEvents(1, false); // Fetch page 1, replace content
                });

                // 3. Category Filter Clicks
                $('.category-filter').on('click', function(e) {
                    e.preventDefault();
                    $('.category-filter').removeClass('active');
                    $(this).addClass('active');
                    fetchEvents(1, false); // Fetch page 1, replace content
                });

                // 4. "See More" Button Click
                $('#see-more-btn').on('click', function() {
                    let nextPage = $(this).data('page') + 1;
                    fetchEvents(nextPage, true); // Fetch next page, append content
                });

                // Optional: Trigger search on dropdown change automatically
                $('#event-type-select').on('change', function() {
                    fetchEvents(1, false);
                });
            });
        </script>
    @endpush
</x-frontend-app-layout>
