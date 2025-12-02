<x-admin-app-layout :title="'Edit Event'">

    <div class="card card-flash">
        <div class="card-header py-4 bg-white d-flex justify-content-between align-items-center">
            <h1 class="h4 fw-semibold mb-0">Edit Event</h1>

            <a href="{{ route('admin.event.index') }}" class="btn btn-light btn-sm border">
                <i class="fas fa-arrow-left me-1 text-primary"></i> Back to List
            </a>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.event.update', $event->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-5">
                    {{-- LEFT SIDE --}}
                    <div class="col-lg-8">

                        <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x fs-6 fw-semibold border-bottom mb-4">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#general_tab">
                                    <i class="bi bi-info-circle me-1"></i> General
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#media_tab">
                                    <i class="bi bi-image me-1"></i> Media
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#content_tab">
                                    <i class="bi bi-card-text me-1"></i> Content
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#venue_tab">
                                    <i class="bi bi-geo-alt me-1"></i> Time & Venue
                                </a>
                            </li>



                        </ul>

                        <div class="tab-content">

                            {{-- ===========================
                                TAB 1 — GENERAL
                            ============================ --}}
                            <div class="tab-pane fade show active" id="general_tab">

                                <div class="card shadow-sm border-0 card-flush">
                                    <div class="card-body">

                                        <h5 class="text-primary mb-5">General Information</h5>

                                        <div class="row g-4">

                                            {{-- EVENT NAME --}}
                                            <div class="col-12">
                                                <x-metronic.label for="name" class="required">Event
                                                    Name</x-metronic.label>
                                                <x-metronic.input id="name" type="text" name="name"
                                                    value="{{ old('name', $event->name) }}"
                                                    placeholder="Enter event name" required />
                                            </div>

                                            {{-- CAPACITY --}}
                                            <div class="col-md-4">
                                                <x-metronic.label for="total_capacity">Total Capacity</x-metronic.label>
                                                <x-metronic.input id="total_capacity" type="number"
                                                    name="total_capacity"
                                                    value="{{ old('total_capacity', $event->total_capacity) }}" />
                                            </div>

                                            {{-- AGE --}}
                                            <div class="col-md-4">
                                                <x-metronic.label for="age_restriction">Age
                                                    Restriction</x-metronic.label>
                                                <x-metronic.input id="age_restriction" type="text"
                                                    name="age_restriction"
                                                    value="{{ old('age_restriction', $event->age_restriction) }}" />
                                            </div>

                                            {{-- DEADLINE --}}
                                            <div class="col-md-4">
                                                <x-metronic.label for="purchase_deadline">Purchase
                                                    Deadline</x-metronic.label>
                                                <x-metronic.input id="purchase_deadline" type="datetime-local"
                                                    name="purchase_deadline"
                                                    value="{{ old('purchase_deadline', optional($event->purchase_deadline)->format('Y-m-d\TH:i')) }}" />
                                            </div>

                                            {{-- TERMS --}}
                                            <div class="col-12">
                                                <x-metronic.label for="terms_and_conditions">Terms &
                                                    Conditions</x-metronic.label>
                                                <x-metronic.textarea id="terms_and_conditions"
                                                    name="terms_and_conditions" rows="4">
                                                    {{ old('terms_and_conditions', $event->terms_and_conditions) }}
                                                </x-metronic.textarea>
                                            </div>

                                        </div>

                                    </div>
                                </div>

                            </div>


                            {{-- ===========================
                                TAB 2 — MEDIA
                            ============================ --}}
                            <div class="tab-pane fade" id="media_tab">

                                <div class="card shadow-sm border-0 card-flush">
                                    <div class="card-body">

                                        <h5 class="text-primary mb-5">Media Uploads</h5>

                                        <div class="row g-4">

                                            {{-- Logo --}}
                                            <div class="col-md-6">
                                                <x-metronic.label for="logo">Logo</x-metronic.label>
                                                <x-metronic.file-input id="logo" name="logo"
                                                    :source="isset($event->logo)
                                                        ? asset('storage/' . $event->logo)
                                                        : null" />
                                            </div>

                                            {{-- Main Image --}}
                                            <div class="col-md-6">
                                                <x-metronic.label for="image">Main Image</x-metronic.label>
                                                <x-metronic.file-input id="image" name="image"
                                                    :source="isset($event->image)
                                                        ? asset('storage/' . $event->image)
                                                        : null" />
                                            </div>

                                            {{-- Banner --}}
                                            <div class="col-md-6">
                                                <x-metronic.label for="banner_image">Banner Image</x-metronic.label>
                                                <x-metronic.file-input id="banner_image" name="banner_image"
                                                    :source="isset($event->banner_image)
                                                        ? asset('storage/' . $event->banner_image)
                                                        : null" />
                                            </div>

                                            {{-- Teaser --}}
                                            <div class="col-md-6">
                                                <x-metronic.label for="video_teaser_url">Video Teaser
                                                    URL</x-metronic.label>
                                                <x-metronic.input id="video_teaser_url" type="url"
                                                    name="video_teaser_url"
                                                    value="{{ old('video_teaser_url', $event->video_teaser_url) }}" />
                                            </div>

                                            {{-- MULTI GALLERY --}}
                                            <div class="col-12">
                                                <label class="form-label fw-semibold">Gallery Images</label>

                                                <div class="p-5 border border-dashed rounded bg-light text-center">
                                                    <i class="bi bi-cloud-arrow-up fs-1 text-primary mb-2"></i>
                                                    <p class="fw-semibold mb-1">Drop or click to upload</p>
                                                    <small class="text-muted d-block mb-2">Max 10 files</small>

                                                    <input type="file" id="multi_img" name="multi_img[]" multiple
                                                        class="d-none" />
                                                </div>

                                                {{-- EXISTING IMAGES --}}
                                                <div class="d-flex gap-3 flex-wrap mt-4">
                                                    @foreach ($event->images as $multi)
                                                        <div class="position-relative border rounded"
                                                            style="width: 120px;">
                                                            <img src="{{ asset('storage/' . $multi->photo) }}"
                                                                class="img-fluid" />
                                                            <button type="button" data-id="{{ $multi->id }}"
                                                                class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 remove-gallery">
                                                                <i class="bi bi-x"></i>
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>

                            </div>


                            {{-- ===========================
                                TAB 3 — CONTENT
                            ============================ --}}
                            <div class="tab-pane fade" id="content_tab">

                                <div class="card shadow-sm border-0 card-flush">
                                    <div class="card-body">

                                        <h5 class="text-primary mb-5">Event Content</h5>

                                        <div class="mb-5">
                                            <x-metronic.label>Description</x-metronic.label>
                                            <textarea name="description" class="ckeditor" rows="6">
                                                {{ old('description', $event->description) }}
                                            </textarea>
                                        </div>

                                        <div class="mt-4">
                                            <x-metronic.label for="tagline">Tagline</x-metronic.label>
                                            <x-metronic.input id="tagline" type="text" name="tagline"
                                                value="{{ old('tagline', $event->tagline) }}" />
                                        </div>

                                    </div>
                                </div>

                            </div>


                            {{-- ===========================
                                TAB 4 — TIME & VENUE
                            ============================ --}}
                            <div class="tab-pane fade" id="venue_tab">

                                <div class="card shadow-sm border-0 card-flush">
                                    <div class="card-body">

                                        <h5 class="text-primary mb-5">Time & Venue</h5>

                                        <div class="row g-4">

                                            {{-- LOCATION URL --}}
                                            <div class="col-12">
                                                <x-metronic.label for="location_map_url">Location Map
                                                    URL</x-metronic.label>
                                                <x-metronic.input id="location_map_url" type="url"
                                                    name="location_map_url"
                                                    value="{{ old('location_map_url', $event->location_map_url) }}" />
                                            </div>

                                            {{-- Dates --}}
                                            <div class="col-md-3">
                                                <x-metronic.label for="start_date">Start Date</x-metronic.label>
                                                <x-metronic.input id="start_date" type="date" name="start_date"
                                                    value="{{ old('start_date', $event->start_date) }}" />
                                            </div>

                                            <div class="col-md-3">
                                                <x-metronic.label for="end_date">End Date</x-metronic.label>
                                                <x-metronic.input id="end_date" type="date" name="end_date"
                                                    value="{{ old('end_date', $event->end_date) }}" />
                                            </div>

                                            <div class="col-md-3">
                                                <x-metronic.label for="start_time">Start Time</x-metronic.label>
                                                <x-metronic.input id="start_time" type="time" name="start_time"
                                                    value="{{ old('start_time', $event->start_time) }}" />
                                            </div>

                                            <div class="col-md-3">
                                                <x-metronic.label for="end_time">End Time</x-metronic.label>
                                                <x-metronic.input id="end_time" type="time" name="end_time"
                                                    value="{{ old('end_time', $event->end_time) }}" />
                                            </div>


                                            {{-- ============= VENUE DROPDOWN ============= --}}
                                            <div class="col-md-6">
                                                <x-metronic.label for="venue_id" class="required">Select
                                                    Venue</x-metronic.label>
                                                <x-metronic.select-option id="venue_id" name="venue_id" required>
                                                    <option value="">Choose venue</option>
                                                    @foreach ($venues as $venue)
                                                        <option value="{{ $venue->id }}"
                                                            {{ old('venue_id', $event->venue_id) == $venue->id ? 'selected' : '' }}>
                                                            {{ $venue->name }}
                                                        </option>
                                                    @endforeach
                                                </x-metronic.select-option>
                                            </div>


                                            {{-- ============= SEATING PLAN ============= --}}
                                            <div class="col-md-6">
                                                <x-metronic.label for="seating_plan_id" class="required">Seating
                                                    Plan</x-metronic.label>

                                                <x-metronic.select-option id="seating_plan_id" name="seating_plan_id"
                                                    required>
                                                    <option value="">Select seating plan</option>

                                                    @foreach ($seatingPlans as $plan)
                                                        <option value="{{ $plan->id }}"
                                                            {{ old('seating_plan_id', $event->seating_plan_id) == $plan->id ? 'selected' : '' }}>
                                                            {{ $plan->name }}
                                                        </option>
                                                    @endforeach
                                                </x-metronic.select-option>

                                                {{-- Designer Button --}}
                                                @if ($event->seating_plan_id)
                                                    <a href="{{ route('admin.seating-plans.designer', $event->seating_plan_id) }}"
                                                        class="btn btn-warning btn-sm mt-2" target="_blank">
                                                        <i class="fa fa-edit me-1"></i>
                                                        Open Seat Map Designer
                                                    </a>
                                                @endif
                                            </div>


                                            {{-- VENUE DESCRIPTION --}}
                                            <div class="col-12">
                                                <x-metronic.label for="venue">Venue Description</x-metronic.label>
                                                <x-metronic.textarea id="venue" name="venue" rows="2">
                                                    {{ old('venue', $event->venue) }}
                                                </x-metronic.textarea>
                                            </div>

                                            {{-- ORGANIZER DETAILS --}}
                                            <div class="col-md-4">
                                                <x-metronic.label for="organizer_name">Organizer
                                                    Name</x-metronic.label>
                                                <x-metronic.input id="organizer_name" type="text"
                                                    name="organizer_name"
                                                    value="{{ old('organizer_name', $event->organizer_name) }}" />
                                            </div>

                                            <div class="col-md-4">
                                                <x-metronic.label for="organizer_brand">Organizer
                                                    Brand</x-metronic.label>
                                                <x-metronic.input id="organizer_brand" type="text"
                                                    name="organizer_brand"
                                                    value="{{ old('organizer_brand', $event->organizer_brand) }}" />
                                            </div>

                                            <div class="col-md-4">
                                                <x-metronic.label for="organizer_logo">Organizer
                                                    Logo</x-metronic.label>
                                                <x-metronic.file-input id="organizer_logo" name="organizer_logo"
                                                    :source="isset($event->organizer_logo)
                                                        ? asset('storage/' . $event->organizer_logo)
                                                        : null" />
                                            </div>

                                            <div class="col-md-4">
                                                <x-metronic.label for="venue_image">Venue Image</x-metronic.label>
                                                <x-metronic.file-input id="venue_image" name="venue_image"
                                                    :source="isset($event->venue_image)
                                                        ? asset('storage/' . $event->venue_image)
                                                        : null" />
                                            </div>

                                        </div>

                                    </div>
                                </div>

                            </div>



                        </div>
                    </div>


                    {{-- RIGHT SIDE --}}
                    <div class="col-lg-4">

                        {{-- STATUS --}}
                        <div class="card shadow-sm border">
                            <div class="card-body">
                                <h6 class="fw-semibold mb-2">Status</h6>
                                <x-metronic.select-option id="status" name="status">
                                    <option value="active" {{ $event->status === 'active' ? 'selected' : '' }}>
                                        Active
                                    </option>

                                    <option value="inactive" {{ $event->status === 'inactive' ? 'selected' : '' }}>
                                        Inactive
                                    </option>
                                </x-metronic.select-option>
                            </div>
                        </div>

                        {{-- FEATURED --}}
                        <div class="card shadow-sm border mt-4">
                            <div class="card-body">
                                <h6 class="fw-semibold mb-2">Featured Event</h6>
                                <x-metronic.select-option id="is_featured" name="is_featured">
                                    <option value="0" {{ $event->is_featured == 0 ? 'selected' : '' }}>No
                                    </option>
                                    <option value="1" {{ $event->is_featured == 1 ? 'selected' : '' }}>Yes
                                    </option>
                                </x-metronic.select-option>
                            </div>
                        </div>

                        {{-- EVENT TYPE --}}
                        <div class="card shadow-sm border mt-4">
                            <div class="card-body">
                                <h6 class="fw-semibold mb-2">Event Type</h6>
                                <x-metronic.select-option id="event_type_id" name="event_type_id" required>
                                    <option value="">Select Type</option>
                                    @foreach ($event_types as $t)
                                        <option value="{{ $t->id }}"
                                            {{ $event->event_type_id == $t->id ? 'selected' : '' }}>
                                            {{ $t->name }}
                                        </option>
                                    @endforeach
                                </x-metronic.select-option>
                            </div>
                        </div>

                    </div>

                </div>

                {{-- SUBMIT --}}
                <div class="mt-5 text-end">
                    <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill shadow-sm">
                        <i class="bi bi-save me-1"></i> Update Event
                    </button>
                </div>

            </form>
        </div>
    </div>

    @include('admin.pages.event.partials.ticket_type_modals')
    {{-- ===========================
        AJAX – Load Seating Plans
    ============================ --}}
    @push('scripts')
        <script>
            $('#venue_id').on('change', function() {
                let venueId = $(this).val();
                let dropdown = $('#seating_plan_id');

                dropdown.html('<option>Loading...</option>');

                $.get('/admin/venue/' + venueId + '/seating-plans', function(res) {
                    let html = '<option value="">Select seating plan</option>';
                    res.forEach(plan => {
                        html += `<option value="${plan.id}">${plan.name}</option>`;
                    });
                    dropdown.html(html);
                });
            });

            // Delete multi image
            $('.remove-gallery').on('click', function() {
                let id = $(this).data('id');
                if (!confirm('Remove this image?')) return;

                $.ajax({
                    url: '/admin/multiimage/' + id,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: () => location.reload()
                });
            });
        </script>
        
    @endpush

</x-admin-app-layout>
