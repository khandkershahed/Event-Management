<x-admin-app-layout :title="'Edit Event'">
    <div class="border-0 shadow-sm card rounded-3">
        <!-- Header -->
        <div class="py-4 bg-white card-header d-flex justify-content-between align-items-center">
            <h1 class="mb-0 h4 fw-semibold">Edit Event</h1>
            <a href="{{ route('admin.event.index') }}" class="border btn btn-light btn-sm">
                <i class="fas fa-arrow-left me-1 text-primary"></i> Back to List
            </a>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.event.update', $event->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-5">
                    <!-- Left Section -->
                    <div class="col-lg-8">
                        <div class="border-0 shadow-none card">
                            <!-- Tabs -->
                            <ul class="mb-4 nav nav-tabs nav-line-tabs nav-line-tabs-2x fs-6 fw-semibold border-bottom">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#general">
                                        <i class="bi bi-info-circle me-1"></i> General
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#media">
                                        <i class="bi bi-image me-1"></i> Media
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#content">
                                        <i class="bi bi-card-text me-1"></i> Content
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#venue">
                                        <i class="bi bi-geo-alt me-1"></i> Time & Venue
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <!-- General -->
                                <div class="tab-pane fade show active" id="general">
                                    <div class="border shadow-sm card card-flush">
                                        <div class="card-body">
                                            <div>
                                                <h5 class="mb-5 text-primary">General Information</h5>
                                            </div>
                                            <div class="row g-4">
                                                <div class="col-12">
                                                    <x-metronic.label for="name" class="required">Event Name</x-metronic.label>
                                                    <x-metronic.input id="name" type="text" name="name" :value="old('name', $event->name)" placeholder="Enter event name" required />
                                                </div>

                                                <div class="col-md-4">
                                                    <x-metronic.label for="total_capacity" class="w-100">Total Capacity</x-metronic.label>
                                                    <x-metronic.input id="total_capacity" type="number" name="total_capacity" :value="old('total_capacity', $event->total_capacity)" />
                                                </div>

                                                <div class="col-md-4">
                                                    <x-metronic.label for="age_restriction" class="w-100">Age Restriction</x-metronic.label>
                                                    <x-metronic.input id="age_restriction" type="text" name="age_restriction"
                                                        :value="old('age_restriction', $event->age_restriction)" placeholder="e.g. 18+, All Ages" />
                                                </div>

                                                <div class="col-md-4">
                                                    <x-metronic.label for="purchase_deadline" class="w-100">Purchase Deadline</x-metronic.label>
                                                    <x-metronic.input id="purchase_deadline" type="datetime-local" name="purchase_deadline"
                                                        :value="old('purchase_deadline', optional($event->purchase_deadline)->format('Y-m-d\TH:i'))" />
                                                </div>

                                                <div class="col-12">
                                                    <x-metronic.label for="terms_and_conditions">Terms & Conditions</x-metronic.label>
                                                    <x-metronic.textarea id="terms_and_conditions" name="terms_and_conditions"
                                                        rows="4" placeholder="Enter terms and conditions">{{ old('terms_and_conditions', $event->terms_and_conditions) }}</x-metronic.textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Media -->
                                <div class="tab-pane fade" id="media">
                                    <div class="border shadow-sm card card-flush">
                                        <div class="card-body">
                                            <div>
                                                <h5 class="mb-5 text-primary">Media Uploads</h5>
                                            </div>
                                            <div class="row g-4">
                                                <div class="col-md-6">
                                                    <x-metronic.label for="logo">Logo</x-metronic.label>
                                                    <x-metronic.file-input id="logo" name="logo" :source="isset($event->logo) ? asset('storage/'.$event->logo) : null" />
                                                </div>

                                                <div class="col-md-6">
                                                    <x-metronic.label for="image">Main Image</x-metronic.label>
                                                    <x-metronic.file-input id="image" name="image" :source="isset($event->image) ? asset('storage/'.$event->image) : null" />
                                                </div>

                                                <div class="col-md-6">
                                                    <x-metronic.label for="banner_image">Banner Image</x-metronic.label>
                                                    <x-metronic.file-input id="banner_image" name="banner_image" :source="isset($event->banner_image) ? asset('storage/'.$event->banner_image) : null" />
                                                </div>

                                                <div class="col-md-6">
                                                    <x-metronic.label for="video_teaser_url">Video Teaser URL</x-metronic.label>
                                                    <x-metronic.input id="video_teaser_url" type="url" name="video_teaser_url"
                                                        :value="old('video_teaser_url', $event->video_teaser_url)" placeholder="https://youtube.com/..." />
                                                </div>

                                                <!-- Multi Image -->
                                                <div class="col-12">
                                                    <label class="form-label fw-semibold">Gallery Images</label>
                                                    <div class="p-5 text-center border border-dashed rounded-3 bg-light">
                                                        <i class="mb-2 bi bi-cloud-arrow-up text-primary fs-1"></i>
                                                        <p class="mb-1 fw-semibold">Drop or click to upload</p>
                                                        <small class="mb-2 text-muted d-block">Max 10 files</small>
                                                        <input type="file" id="files" name="multi_img[]" multiple class="d-none" />
                                                    </div>

                                                    <!-- Existing Images -->
                                                    <div class="flex-wrap gap-3 mt-4 d-flex">
                                                        @foreach ($event->images as $image)
                                                        <div class="overflow-hidden border rounded position-relative" style="width: 120px;">
                                                            <img src="{{ asset('storage/' . $image->photo) }}" class="img-fluid" alt="">
                                                            <a href="{{ route('admin.multiimage.destroy', $image->id) }}" class="top-0 m-1 btn btn-sm btn-danger position-absolute end-0">
                                                                <i class="bi bi-x"></i>
                                                            </a>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="tab-pane fade" id="content">
                                    <div class="border shadow-sm card card-flush">
                                        <div class="card-body">
                                            <div>
                                                <h5 class="mb-5 text-primary">Event Content</h5>
                                            </div>
                                            <x-metronic.label for="description">Description</x-metronic.label>
                                            <textarea id="description" name="description" class="ckeditor" rows="6">{{ old('description', $event->description) }}</textarea>

                                            <div class="mt-4">
                                                <x-metronic.label for="tagline">Tagline</x-metronic.label>
                                                <x-metronic.input id="tagline" type="text" name="tagline" :value="old('tagline', $event->tagline)" placeholder="Short tagline" />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Venue -->
                                <div class="tab-pane fade" id="venue">
                                    <div class="border shadow-sm card card-flush">
                                        <div class="card-body">
                                            <div>
                                                <h5 class="mb-5 text-primary">Time & Venue</h5>
                                            </div>
                                            <div class="row g-4">
                                                <div class="col-12">
                                                    <x-metronic.label for="location_map_url">Location Map URL</x-metronic.label>
                                                    <x-metronic.input id="location_map_url" type="url" name="location_map_url"
                                                        :value="old('location_map_url', $event->location_map_url)" placeholder="https://maps.google.com/..." />
                                                </div>

                                                <div class="col-md-3">
                                                    <x-metronic.label for="start_date" class="w-100">Start Date</x-metronic.label>
                                                    <x-metronic.input id="start_date" type="date" name="start_date" :value="old('start_date', $event->start_date)" />
                                                </div>

                                                <div class="col-md-3">
                                                    <x-metronic.label for="end_date" class="w-100">End Date</x-metronic.label>
                                                    <x-metronic.input id="end_date" type="date" name="end_date" :value="old('end_date', $event->end_date)" />
                                                </div>

                                                <div class="col-md-3">
                                                    <x-metronic.label for="start_time" class="w-100">Start Time</x-metronic.label>
                                                    <x-metronic.input id="start_time" type="time" name="start_time" :value="old('start_time', $event->start_time)" />
                                                </div>

                                                <div class="col-md-3">
                                                    <x-metronic.label for="end_time" class="w-100">End Time</x-metronic.label>
                                                    <x-metronic.input id="end_time" type="time" name="end_time" :value="old('end_time', $event->end_time)" />
                                                </div>

                                                <div class="col-md-4">
                                                    <x-metronic.label for="organizer_logo" class="w-100">Organizer Logo</x-metronic.label>
                                                    <x-metronic.file-input id="organizer_logo" name="organizer_logo" :source="isset($event->organizer_logo) ? asset('storage/'.$event->organizer_logo) : null" />
                                                </div>

                                                <div class="col-md-4">
                                                    <x-metronic.label for="organizer_name" class="w-100">Organizer Name</x-metronic.label>
                                                    <x-metronic.input id="organizer_name" type="text" name="organizer_name"
                                                        :value="old('organizer_name', $event->organizer_name)" placeholder="Organizer name" />
                                                </div>

                                                <div class="col-md-4">
                                                    <x-metronic.label for="organizer_brand" class="w-100">Organizer Brand</x-metronic.label>
                                                    <x-metronic.input id="organizer_brand" type="text" name="organizer_brand"
                                                        :value="old('organizer_brand', $event->organizer_brand)" placeholder="Brand name" />
                                                </div>

                                                <div class="col-12">
                                                    <x-metronic.label for="venue_image">Venue Image</x-metronic.label>
                                                    <x-metronic.file-input id="venue_image" name="venue_image" :source="isset($event->venue_image) ? asset('storage/'.$event->venue_image) : null" />
                                                </div>

                                                <div class="col-12">
                                                    <x-metronic.label for="venue">Venue</x-metronic.label>
                                                    <x-metronic.textarea id="venue" name="venue" rows="2" placeholder="Event venue">{{ old('venue', $event->venue) }}</x-metronic.textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Sidebar -->
                    <div class="col-lg-4">
                        <div class="gap-4 d-flex flex-column">
                            <!-- Status -->
                            <div class="border shadow-sm card">
                                <div class="card-body">
                                    <h6 class="fw-semibold">Status</h6>
                                    <x-metronic.select-option id="status" name="status">
                                        <option value="active" {{ old('status', $event->status) === 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status', $event->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </x-metronic.select-option>
                                    <small class="text-muted">Set the event status</small>
                                </div>
                            </div>

                            <!-- Featured -->
                            <div class="border shadow-sm card">
                                <div class="card-body">
                                    <h6 class="fw-semibold">Featured Event</h6>
                                    <x-metronic.select-option id="is_featured" name="is_featured">
                                        <option value="0" {{ old('is_featured', $event->is_featured) == '0' ? 'selected' : '' }}>No</option>
                                        <option value="1" {{ old('is_featured', $event->is_featured) == '1' ? 'selected' : '' }}>Yes</option>
                                    </x-metronic.select-option>
                                </div>
                            </div>

                            <!-- Event Type -->
                            <div class="border shadow-sm card">
                                <div class="pt-0 card-body">
                                    <h6 class="mb-0 fw-semibold">Event Type</h6>
                                    <x-metronic.select-option id="event_type_id" name="event_type_id" required>
                                        <option></option>
                                        @foreach ($event_types as $event_type)
                                        <option value="{{ $event_type->id }}" {{ old('event_type_id', $event->event_type_id) == $event_type->id ? 'selected' : '' }}>
                                            {{ $event_type->name }}
                                        </option>
                                        @endforeach
                                    </x-metronic.select-option>
                                    <small class="text-muted">Choose event type</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="mt-5 text-end">
                    <button type="submit" class="px-5 py-2 shadow-sm btn btn-primary rounded-pill">
                        <i class="bi bi-save me-1"></i> Update Event
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-app-layout>