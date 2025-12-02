<x-admin-app-layout :title="'Create Event'">

    <div class="card card-flash">
        <div class="mt-6 card-header">
            <div class="card-toolbar">
                <a href="{{ route('admin.event.index') }}" class="btn btn-light-info">
                    <span class="svg-icon svg-icon-3"><i class="fas fa-arrow-left"></i></span>
                    Back to the list
                </a>
            </div>
        </div>

        <div class="pt-0 card-body">
            <form method="POST" action="{{ route('admin.event.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    {{-- LEFT SIDE --}}
                    <div class="col-8 gap-7 gap-lg-10">

                        <ul
                            class="border-0 nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x fs-4 fw-semibold mb-n2">
                            <li class="nav-item">
                                <a class="pb-4 nav-link text-active-primary active" data-bs-toggle="tab"
                                    href="#general">
                                    General
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="pb-4 nav-link text-active-primary" data-bs-toggle="tab" href="#media">
                                    Media
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="pb-4 nav-link text-active-primary" data-bs-toggle="tab" href="#content">
                                    Event Content
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="pb-4 nav-link text-active-primary" data-bs-toggle="tab" href="#venue_tab">
                                    Time & Venue
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">

                            {{-- =============================
                                 TAB 1 — GENERAL
                            ============================== --}}
                            <div class="tab-pane fade show active" id="general">
                                <div class="d-flex flex-column gap-7 gap-lg-10">
                                    <div class="py-4 mt-3 card card-flush">
                                        <div class="card-header">
                                            <h2>General</h2>
                                        </div>

                                        <div class="pt-0 card-body">
                                            <div class="row">

                                                <div class="col-lg-12 mb-7">
                                                    <x-metronic.label for="name" class="required">
                                                        Event Name
                                                    </x-metronic.label>
                                                    <x-metronic.input type="text" id="name" name="name"
                                                        value="{{ old('name') }}" placeholder="Enter event name"
                                                        required />
                                                </div>

                                                <div class="col-lg-4 mb-7">
                                                    <x-metronic.label for="total_capacity">
                                                        Total Capacity
                                                    </x-metronic.label>
                                                    <x-metronic.input type="number" id="total_capacity"
                                                        name="total_capacity" value="{{ old('total_capacity') }}" />
                                                </div>

                                                <div class="col-lg-4 mb-7">
                                                    <x-metronic.label for="age_restriction">
                                                        Age Restriction
                                                    </x-metronic.label>
                                                    <x-metronic.input id="age_restriction" type="text"
                                                        name="age_restriction" placeholder="e.g. 18+"
                                                        value="{{ old('age_restriction') }}" />
                                                </div>

                                                <div class="col-lg-4 mb-7">
                                                    <x-metronic.label for="purchase_deadline">
                                                        Purchase Deadline
                                                    </x-metronic.label>
                                                    <x-metronic.input id="purchase_deadline" type="datetime-local"
                                                        name="purchase_deadline"
                                                        value="{{ old('purchase_deadline') }}" />
                                                </div>

                                                <div class="col-lg-12 mb-7">
                                                    <x-metronic.label for="terms_and_conditions">
                                                        Terms & Conditions
                                                    </x-metronic.label>
                                                    <x-metronic.textarea id="terms_and_conditions"
                                                        name="terms_and_conditions"
                                                        rows="3">{{ old('terms_and_conditions') }}</x-metronic.textarea>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            {{-- =============================
                                 TAB 2 — MEDIA
                            ============================== --}}
                            <div class="tab-pane fade" id="media">
                                <div class="d-flex flex-column gap-7 gap-lg-10">

                                    <div class="py-4 card card-flush">
                                        <div class="card-header">
                                            <h2>Media</h2>
                                        </div>

                                        <div class="pt-0 card-body">
                                            <div class="row">

                                                {{-- Logo --}}
                                                <div class="col-lg-6 mb-7">
                                                    <x-metronic.label for="logo">Logo</x-metronic.label>
                                                    <x-metronic.file-input id="logo" name="logo" />
                                                </div>

                                                {{-- Main Image --}}
                                                <div class="col-lg-6 mb-7">
                                                    <x-metronic.label for="image">Main Image</x-metronic.label>
                                                    <x-metronic.file-input id="image" name="image" />
                                                </div>

                                                {{-- Banner --}}
                                                <div class="col-lg-4 mb-7">
                                                    <x-metronic.label for="banner_image">Banner Image</x-metronic.label>
                                                    <x-metronic.file-input id="banner_image" name="banner_image" />
                                                </div>

                                                {{-- Video Teaser --}}
                                                <div class="col-lg-8 mb-7">
                                                    <x-metronic.label for="video_teaser_url">Video Teaser
                                                        URL</x-metronic.label>
                                                    <x-metronic.textarea id="video_teaser_url" name="video_teaser_url"
                                                        rows="2">{{ old('video_teaser_url') }}</x-metronic.textarea>
                                                </div>

                                            </div>

                                            {{-- Multi Gallery Images --}}
                                            <div class="row">
                                                <div class="p-5 border-dashed border bg-light rounded-2">
                                                    <x-metronic.label>Add Gallery Images</x-metronic.label>

                                                    <label for="multi_images" class="custom-file-upload d-flex gap-3">
                                                        <i class="bi bi-cloud-arrow-up fs-2x text-primary"></i>
                                                        <span>
                                                            Drop files here or click to upload <br>
                                                            <small class="text-muted">Up to 10 files</small>
                                                        </span>
                                                    </label>

                                                    <input type="file" id="multi_images" name="multi_images[]"
                                                        multiple class="d-none" />
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>


                            {{-- =============================
                                 TAB 3 — CONTENT
                            ============================== --}}
                            <div class="tab-pane fade" id="content">
                                <div class="d-flex flex-column gap-7 gap-lg-10">

                                    <div class="py-4 mt-3 card card-flush">
                                        <div class="card-header">
                                            <h2>Event Content</h2>
                                        </div>

                                        <div class="pt-0 card-body">

                                            <div class="mb-5">
                                                <x-metronic.label>Event Description</x-metronic.label>
                                                <textarea name="description" class="ckeditor">{!! old('description') !!}</textarea>
                                            </div>

                                            <div class="mb-5">
                                                <x-metronic.label for="tagline">Tagline</x-metronic.label>
                                                <x-metronic.textarea id="tagline" name="tagline"
                                                    rows="2">{{ old('tagline') }}</x-metronic.textarea>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>


                            {{-- =============================
                                 TAB 4 — TIME & VENUE
                            ============================== --}}
                            <div class="tab-pane fade" id="venue_tab">
                                <div class="d-flex flex-column gap-7 gap-lg-10">

                                    <div class="py-4 mt-3 card card-flush">
                                        <div class="card-header">
                                            <h2>Time & Venue</h2>
                                        </div>

                                        <div class="pt-0 card-body">

                                            <div class="row">

                                                {{-- Map URL --}}
                                                <div class="col-lg-8 mb-7">
                                                    <x-metronic.label for="location_map_url">Location Map
                                                        URL</x-metronic.label>
                                                    <x-metronic.input id="location_map_url" type="url"
                                                        name="location_map_url"
                                                        value="{{ old('location_map_url') }}" />
                                                </div>

                                                {{-- Dates & Times --}}
                                                <div class="col-lg-4 mb-7">
                                                    <x-metronic.label for="start_date">Start Date</x-metronic.label>
                                                    <x-metronic.input id="start_date" type="date"
                                                        name="start_date" value="{{ old('start_date') }}" />
                                                </div>

                                                <div class="col-lg-4 mb-7">
                                                    <x-metronic.label for="end_date">End Date</x-metronic.label>
                                                    <x-metronic.input id="end_date" type="date" name="end_date"
                                                        value="{{ old('end_date') }}" />
                                                </div>

                                                <div class="col-lg-4 mb-7">
                                                    <x-metronic.label for="start_time">Start Time</x-metronic.label>
                                                    <x-metronic.input id="start_time" type="time"
                                                        name="start_time" value="{{ old('start_time') }}" />
                                                </div>

                                                <div class="col-lg-4 mb-7">
                                                    <x-metronic.label for="end_time">End Time</x-metronic.label>
                                                    <x-metronic.input id="end_time" type="time" name="end_time"
                                                        value="{{ old('end_time') }}" />
                                                </div>

                                                {{-- VENUE DROPDOWN --}}
                                                <div class="col-lg-6 mb-7">
                                                    <x-metronic.label for="venue_id" class="required">Select
                                                        Venue</x-metronic.label>
                                                    <x-metronic.select-option id="venue_id" name="venue_id" required>
                                                        <option value="">Choose venue</option>
                                                        @foreach ($venues as $venue)
                                                            <option value="{{ $venue->id }}"
                                                                {{ old('venue_id') == $venue->id ? 'selected' : '' }}>
                                                                {{ $venue->name }}
                                                            </option>
                                                        @endforeach
                                                    </x-metronic.select-option>
                                                </div>

                                                {{-- SEATING PLAN DROPDOWN --}}
                                                <div class="col-lg-6 mb-7">
                                                    <x-metronic.label for="seating_plan_id" class="required">
                                                        Seating Plan
                                                    </x-metronic.label>

                                                    <x-metronic.select-option id="seating_plan_id"
                                                        name="seating_plan_id" required>
                                                        <option value="">Select seating plan</option>
                                                    </x-metronic.select-option>

                                                    <small class="text-muted mt-2 d-block">
                                                        Layout will load based on venue selection.
                                                    </small>
                                                </div>

                                                {{-- ORGANIZER & VENUE DETAILS --}}
                                                <div class="col-lg-12 mb-7">
                                                    <x-metronic.label for="venue">Venue
                                                        Description</x-metronic.label>
                                                    <x-metronic.textarea id="venue" name="venue"
                                                        rows="2">{{ old('venue') }}</x-metronic.textarea>
                                                </div>

                                                <div class="col-lg-4 mb-7">
                                                    <x-metronic.label for="organizer_name">Organizer
                                                        Name</x-metronic.label>
                                                    <x-metronic.input id="organizer_name" type="text"
                                                        name="organizer_name" value="{{ old('organizer_name') }}" />
                                                </div>

                                                <div class="col-lg-4 mb-7">
                                                    <x-metronic.label for="organizer_brand">Organizer
                                                        Brand</x-metronic.label>
                                                    <x-metronic.input id="organizer_brand" type="text"
                                                        name="organizer_brand"
                                                        value="{{ old('organizer_brand') }}" />
                                                </div>

                                                <div class="col-lg-4 mb-7">
                                                    <x-metronic.label for="organizer_logo">Organizer
                                                        Logo</x-metronic.label>
                                                    <x-metronic.file-input id="organizer_logo"
                                                        name="organizer_logo" />
                                                </div>

                                                <div class="col-lg-4 mb-7">
                                                    <x-metronic.label for="venue_image">Venue Image</x-metronic.label>
                                                    <x-metronic.file-input id="venue_image" name="venue_image" />
                                                </div>

                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>


                        </div>

                        {{-- Submit --}}
                        <div class="mt-10 d-flex justify-content-end">
                            <a href="{{ route('admin.event.index') }}" class="btn btn-danger me-5">
                                Back To Events List
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <span class="indicator-label">Save Changes</span>
                            </button>
                        </div>

                    </div>


                    {{-- RIGHT SIDEBAR --}}
                    <div class="col-4 gap-7 gap-lg-10">

                        {{-- Status --}}
                        <div class="py-2 card card-flush">
                            <div class="card-header">
                                <h2>Status</h2>
                            </div>
                            <div class="py-0 card-body">
                                <x-metronic.select-option id="status" name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>
                                        Active
                                    </option>
                                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>
                                        Inactive
                                    </option>
                                </x-metronic.select-option>
                            </div>
                        </div>

                        {{-- Featured --}}
                        <div class="py-2 card card-flush">
                            <div class="card-header">
                                <h2>Featured Event?</h2>
                            </div>
                            <div class="py-0 card-body">
                                <x-metronic.select-option id="is_featured" name="is_featured">
                                    <option value="0" {{ old('is_featured') == 0 ? 'selected' : '' }}>No</option>
                                    <option value="1" {{ old('is_featured') == 1 ? 'selected' : '' }}>Yes
                                    </option>
                                </x-metronic.select-option>
                            </div>
                        </div>

                        {{-- Event Type --}}
                        <div class="py-2 card card-flush">
                            <div class="card-header">
                                <h2>Event Type</h2>
                            </div>
                            <div class="py-0 card-body">
                                <x-metronic.select-option id="event_type_id" name="event_type_id" required>
                                    <option value="">Select Type</option>
                                    @foreach ($event_types as $type)
                                        <option value="{{ $type->id }}"
                                            {{ old('event_type_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </x-metronic.select-option>
                            </div>
                        </div>

                    </div>

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
                let planDropdown = $('#seating_plan_id');

                planDropdown.html('<option>Loading...</option>');

                $.get('/admin/venue/' + venueId + '/seating-plans', function(res) {
                    let html = '<option value="">Select seating plan</option>';
                    res.forEach(plan => {
                        html += `<option value="${plan.id}">${plan.name}</option>`;
                    });
                    planDropdown.html(html);
                });
            });
        </script>
        
    @endpush

</x-admin-app-layout>
