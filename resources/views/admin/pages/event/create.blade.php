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
                    <div class="gap-7 gap-lg-10 col-8">
                        <ul
                            class="border-0 nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x fs-4 fw-semibold mb-n2">
                            <li class="nav-item">
                                <a class="pb-4 nav-link text-active-primary active" data-bs-toggle="tab"
                                    href="#general">General</a>
                            </li>
                            <li class="nav-item">
                                <a class="pb-4 nav-link text-active-primary" data-bs-toggle="tab"
                                    href="#media">Media</a>
                            </li>
                            <li class="nav-item">
                                <a class="pb-4 nav-link text-active-primary" data-bs-toggle="tab"
                                    href="#news_content">Events
                                    Content</a>
                            </li>
                            <li class="nav-item">
                                <a class="pb-4 nav-link text-active-primary" data-bs-toggle="tab" href="#news_type">Time
                                    and Venue</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="general" role="tab-panel">
                                <div class="d-flex flex-column gap-7 gap-lg-10">
                                    {{-- General Info --}}
                                    <div class="py-4 mt-3 card card-flush">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>General</h2>
                                            </div>
                                        </div>
                                        <div class="pt-0 card-body">
                                            <div class="row">
                                                <div class="col-lg-12 mb-7">
                                                    <x-metronic.label for="name"
                                                        class="col-form-label required fw-bold fs-6">
                                                        {{ __('Event Name') }}
                                                    </x-metronic.label>
                                                    <x-metronic.input id="name" type="text" name="name"
                                                        placeholder="Enter event name" :value="old('name')" required />
                                                </div>
                                                {{-- Total Capacity, Age Restriction --}}
                                                <div class="col-lg-4 mb-7">
                                                    <x-metronic.label for="total_capacity"
                                                        class="col-form-label fw-bold fs-6">
                                                        {{ __('Total Capacity') }}
                                                    </x-metronic.label>
                                                    <x-metronic.input id="total_capacity" type="number"
                                                        name="total_capacity" :value="old('total_capacity')" />
                                                </div>
                                                <div class="col-lg-4 mb-7">
                                                    <x-metronic.label for="age_restriction"
                                                        class="col-form-label fw-bold fs-6">
                                                        {{ __('Age Restriction') }}
                                                    </x-metronic.label>
                                                    <x-metronic.input id="age_restriction" type="text"
                                                        name="age_restriction" placeholder="e.g., 18+, All Ages"
                                                        :value="old('age_restriction')" />
                                                </div>
                                                {{-- Purchase Deadline --}}
                                                <div class="col-lg-4 mb-7">
                                                    <x-metronic.label for="purchase_deadline"
                                                        class="col-form-label fw-bold fs-6">
                                                        {{ __('Purchase Deadline') }}
                                                    </x-metronic.label>
                                                    <x-metronic.input id="purchase_deadline" type="datetime-local"
                                                        name="purchase_deadline" :value="old('purchase_deadline')" />
                                                </div>
                                                {{-- Terms & Conditions --}}
                                                <div class="col-lg-12 mb-7">
                                                    <x-metronic.label for="terms_and_conditions"
                                                        class="col-form-label fw-bold fs-6">
                                                        {{ __('Terms & Conditions') }}
                                                    </x-metronic.label>
                                                    <x-metronic.textarea id="terms_and_conditions"
                                                        name="terms_and_conditions"
                                                        placeholder="Enter any terms or conditions">{{ old('terms_and_conditions') }}</x-metronic.textarea>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="media" role="tab-panel">
                                <div class="d-flex flex-column gap-7 gap-lg-10">
                                    {{-- Inventory --}}
                                    <div class="py-4 card card-flush">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>Media</h2>
                                            </div>
                                        </div>
                                        <div class="py-4 mt-3 card-body">
                                            <div class="mb-3 row">
                                                {{-- Media Inputs --}}
                                                <div class="col-lg-6 mb-7">
                                                    <x-metronic.label for="logo"
                                                        class="col-form-label fw-bold fs-6">
                                                        {{ __('Logo') }}
                                                    </x-metronic.label>
                                                    <x-metronic.file-input id="logo" name="logo" />
                                                </div>
                                                <div class="col-lg-6 mb-7">
                                                    <x-metronic.label for="image"
                                                        class="col-form-label fw-bold fs-6">
                                                        {{ __('Image') }}
                                                    </x-metronic.label>
                                                    <x-metronic.file-input id="image" name="image" />
                                                </div>
                                                <div class="col-lg-4 mb-7">
                                                    <x-metronic.label for="banner_image"
                                                        class="col-form-label fw-bold fs-6">
                                                        {{ __('Banner Image') }}
                                                    </x-metronic.label>
                                                    <x-metronic.file-input id="banner_image" name="banner_image" />
                                                </div>

                                                {{-- Video Teaser URL --}}
                                                <div class="col-lg-8 mb-7">
                                                    <x-metronic.label for="video_teaser_url"
                                                        class="col-form-label fw-bold fs-6">
                                                        {{ __('Video Teaser URL') }}
                                                    </x-metronic.label>

                                                    <x-metronic.textarea id="video_teaser_url" name="video_teaser_url"
                                                        placeholder="Enter Video Teaser URL">{{ old('video_teaser_url') }}</x-metronic.textarea>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="p-5 border-dashed fv-row border-1">
                                                    <x-metronic.label for="" class="form-label">Add the
                                                        Events Gallery Images</x-metronic.label>
                                                    <div class="dropzone-field">
                                                        <label for="files" class="custom-file-upload">
                                                            <div class="d-flex align-items-center">
                                                                <p class="mb-0"><i
                                                                        class="bi bi-file-earmark-arrow-up text-primary fs-3x"></i>
                                                                </p>
                                                                <h5 class="mb-0">Drop files here or click to upload.
                                                                    <br>
                                                                    <span class="text-muted"
                                                                        style="font-size: 10px">Upload 10 File</span>
                                                                </h5>
                                                            </div>
                                                        </label>
                                                        <input type="file" id="files" name="multi_images[]"
                                                            multiple class="form-control" style="display: none;"
                                                            onchange="console.log(this.selected.value)" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="news_content" role="tab-panel">
                                <div class="d-flex flex-column gap-7 gap-lg-10">
                                    {{-- Inventory --}}
                                    <div class="py-4 mt-3 card card-flush">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>Events Content</h2>
                                            </div>
                                        </div>
                                        <div class="pt-0 card-body row">
                                            <div class="mb-5 fv-row">
                                                <x-metronic.label class="form-label">Events
                                                    Description</x-metronic.label>
                                                <textarea name="description" class="ckeditor">{!! old('description') !!}</textarea>
                                                <div class="text-muted fs-7">
                                                    Add Events Description here.
                                                </div>
                                            </div>
                                            <div class="mb-5 fv-row">
                                                <x-metronic.label for="tagline" class="col-form-label fw-bold fs-6">
                                                    {{ __('Tagline') }}
                                                </x-metronic.label>
                                                <x-metronic.textarea id="tagline" name="tagline"
                                                    placeholder="Enter any terms or conditions">{{ old('tagline') }}</x-metronic.textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="news_type" role="tab-panel">
                                <div class="d-flex flex-column gap-7 gap-lg-10">
                                    <div class="py-4 mt-3 card card-flush">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>Time and Venue</h2>
                                            </div>
                                        </div>
                                        <div class="pt-0 card-body">
                                            <div class="row">
                                                {{-- Location Map URL --}}
                                                <div class="col-lg-8 mb-7">
                                                    <x-metronic.label for="location_map_url"
                                                        class="col-form-label fw-bold fs-6">
                                                        {{ __('Location Map URL') }}
                                                    </x-metronic.label>
                                                    <x-metronic.input id="location_map_url" type="url"
                                                        name="location_map_url" placeholder="Enter map URL"
                                                        :value="old('location_map_url')" />
                                                </div>

                                                {{-- Start / End Date and Time --}}
                                                <div class="col-lg-4 mb-7">
                                                    <x-metronic.label for="start_date"
                                                        class="col-form-label fw-bold fs-6">
                                                        {{ __('Start Date') }}
                                                    </x-metronic.label>
                                                    <x-metronic.input id="start_date" type="date"
                                                        name="start_date" :value="old('start_date')" />
                                                </div>
                                                <div class="col-lg-4 mb-7">
                                                    <x-metronic.label for="end_date"
                                                        class="col-form-label fw-bold fs-6">
                                                        {{ __('End Date') }}
                                                    </x-metronic.label>
                                                    <x-metronic.input id="end_date" type="date" name="end_date"
                                                        :value="old('end_date')" />
                                                </div>
                                                <div class="col-lg-4 mb-7">
                                                    <x-metronic.label for="start_time"
                                                        class="col-form-label fw-bold fs-6">
                                                        {{ __('Start Time') }}
                                                    </x-metronic.label>
                                                    <x-metronic.input id="start_time" type="time"
                                                        name="start_time" :value="old('start_time')" />
                                                </div>
                                                <div class="col-lg-4 mb-7">
                                                    <x-metronic.label for="end_time"
                                                        class="col-form-label fw-bold fs-6">
                                                        {{ __('End Time') }}
                                                    </x-metronic.label>
                                                    <x-metronic.input id="end_time" type="time" name="end_time"
                                                        :value="old('end_time')" />
                                                </div>

                                                {{-- Venue --}}
                                                <div class="col-lg-8 mb-7">
                                                    <x-metronic.label for="venue"
                                                        class="col-form-label fw-bold fs-6">
                                                        {{ __('Venue') }}
                                                    </x-metronic.label>
                                                    <x-metronic.textarea id="venue" name="venue"
                                                        placeholder="Event venue">{{ old('venue') }}</x-metronic.textarea>
                                                </div>

                                                {{-- Organizer Details --}}
                                                <div class="col-lg-4 mb-7">
                                                    <x-metronic.label for="organizer_name"
                                                        class="col-form-label fw-bold fs-6">
                                                        {{ __('Organizer Name') }}
                                                    </x-metronic.label>
                                                    <x-metronic.input id="organizer_name" type="text"
                                                        name="organizer_name" placeholder="Organizer name"
                                                        :value="old('organizer_name')" />
                                                </div>
                                                <div class="col-lg-4 mb-7">
                                                    <x-metronic.label for="organizer_brand"
                                                        class="col-form-label fw-bold fs-6">
                                                        {{ __('Organizer Brand') }}
                                                    </x-metronic.label>
                                                    <x-metronic.input id="organizer_brand" type="text"
                                                        name="organizer_brand" placeholder="Organizer brand"
                                                        :value="old('organizer_brand')" />
                                                </div>
                                                <div class="col-lg-4 mb-7">
                                                    <x-metronic.label for="organizer_logo"
                                                        class="col-form-label fw-bold fs-6">
                                                        {{ __('Organizer Logo') }}
                                                    </x-metronic.label>
                                                    <x-metronic.file-input id="organizer_logo" name="organizer_logo" />
                                                </div>
                                                <div class="col-lg-4 mb-7">
                                                    <x-metronic.label for="venue_image"
                                                        class="col-form-label fw-bold fs-6">
                                                        {{ __('Venue Image') }}
                                                    </x-metronic.label>
                                                    <x-metronic.file-input id="venue_image" name="venue_image" />
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="mt-10 d-flex justify-content-end">
                            <a href="{{ route('admin.event.index') }}" class="btn btn-danger me-5">
                                Back To Events List
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <span class="indicator-label"> Save Changes </span>
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="gap-7 gap-lg-10 mb-7 col-4">
                        {{-- Status Card Start --}}
                        <div class="py-2 card card-flush">
                            <div class="card-header">
                                <div class="py-1 card-title">
                                    <h2>Status</h2>
                                </div>
                            </div>
                            <div class="py-0 card-body">
                                <x-metronic.select-option id="status" class="form-select" data-control="select2"
                                    data-hide-search="true" name="status" data-placeholder="Select an option">
                                    <option></option>
                                    <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>
                                        Inactive</option>
                                </x-metronic.select-option>
                                <div class="text-muted fs-7">Set the Event status.</div>
                            </div>
                        </div>
                        <div class="py-2 card card-flush">
                            <div class="card-header">
                                <div class="py-1 card-title">
                                    <h2>Featured Event?</h2>
                                </div>
                            </div>
                            <div class="py-0 card-body">
                                <x-metronic.select-option id="is_featured" name="is_featured"
                                    data-hide-search="true">
                                    <option value="0" {{ old('is_featured') == '0' ? 'selected' : '' }}>No
                                    </option>
                                    <option value="1" {{ old('is_featured') == '1' ? 'selected' : '' }}>Yes
                                    </option>
                                </x-metronic.select-option>
                            </div>
                        </div>
                        {{-- Status Card End --}}
                        {{-- Category Card Start --}}
                        <div class="py-2 card card-flush">
                            <div class="card-header">
                                <div class="py-1 card-title">
                                    <h2>Event Type</h2>
                                </div>
                            </div>
                            <div class="py-0 card-body">
                                <x-metronic.select-option id="event_type_id" name="event_type_id"
                                    data-hide-search="true" data-placeholder="Select an option" required>
                                    <option></option>
                                    @foreach ($event_types as $event_type)
                                        <option value="{{ $event_type->id }}"
                                            {{ old('event_type_id') == $event_type->id ? 'selected' : '' }}>
                                            {{ $event_type->name }}
                                        </option>
                                    @endforeach
                                </x-metronic.select-option>
                                <div class="text-muted fs-7">Set the Event Type.</div>
                            </div>
                        </div>
                        {{-- Category Card End --}}
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-admin-app-layout>
