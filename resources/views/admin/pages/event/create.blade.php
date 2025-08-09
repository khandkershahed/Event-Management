<x-admin-app-layout :title="'Create Event'">
    <div class="card card-flash">
        <div class="card-header mt-6">
            <div class="card-toolbar">
                <a href="{{ route('admin.event.index') }}" class="btn btn-light-info">
                    <span class="svg-icon svg-icon-3"><i class="fas fa-arrow-left"></i></span>
                    Back to the list
                </a>
            </div>
        </div>
        <div class="card-body pt-0">
            <form method="POST" action="{{ route('admin.event.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">

                    {{-- Event Type --}}
                    <div class="col-lg-4 mb-7">
                        <x-metronic.label for="event_type_id" class="col-form-label required fw-bold fs-6">
                            {{ __('Select Event Type') }}
                        </x-metronic.label>
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
                    </div>

                    {{-- Name --}}
                    <div class="col-lg-5 mb-7">
                        <x-metronic.label for="name" class="col-form-label required fw-bold fs-6">
                            {{ __('Event Name') }}
                        </x-metronic.label>
                        <x-metronic.input id="name" type="text" name="name"
                            placeholder="Enter event name" :value="old('name')" required />
                    </div>

                    {{-- Status --}}
                    <div class="col-lg-3 mb-7">
                        <x-metronic.label for="status" class="col-form-label fw-bold fs-6">
                            {{ __('Status') }}
                        </x-metronic.label>
                        <x-metronic.select-option id="status" name="status" data-hide-search="true">
                            <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </x-metronic.select-option>
                    </div>
                    {{-- Tagline --}}
                    <div class="col-lg-12 mb-7">
                        <x-metronic.label for="tagline" class="col-form-label fw-bold fs-6">
                            {{ __('Tagline') }}
                        </x-metronic.label>
                        <x-metronic.input id="tagline" type="text" name="tagline"
                            placeholder="Short tagline" :value="old('tagline')" />
                    </div>

                    {{-- Description --}}
                    <div class="col-lg-12 mb-7">
                        <x-metronic.label for="description" class="col-form-label fw-bold fs-6">
                            {{ __('Description') }}
                        </x-metronic.label>
                        <x-metronic.textarea id="description" name="description"
                            placeholder="Event description">{{ old('description') }}</x-metronic.textarea>
                    </div>

                    {{-- Media Inputs --}}
                    <div class="col-lg-4 mb-7">
                        <x-metronic.label for="logo" class="col-form-label fw-bold fs-6">
                            {{ __('Logo') }}
                        </x-metronic.label>
                        <x-metronic.file-input id="logo" name="logo" />
                    </div>
                    <div class="col-lg-4 mb-7">
                        <x-metronic.label for="image" class="col-form-label fw-bold fs-6">
                            {{ __('Image') }}
                        </x-metronic.label>
                        <x-metronic.file-input id="image" name="image" />
                    </div>
                    <div class="col-lg-4 mb-7">
                        <x-metronic.label for="banner_image" class="col-form-label fw-bold fs-6">
                            {{ __('Banner Image') }}
                        </x-metronic.label>
                        <x-metronic.file-input id="banner_image" name="banner_image" />
                    </div>

                    {{-- Video Teaser URL --}}
                    <div class="col-lg-6 mb-7">
                        <x-metronic.label for="video_teaser_url" class="col-form-label fw-bold fs-6">
                            {{ __('Video Teaser URL') }}
                        </x-metronic.label>
                        <x-metronic.input id="video_teaser_url" type="url" name="video_teaser_url"
                            placeholder="Enter video teaser URL" :value="old('video_teaser_url')" />
                    </div>

                    {{-- Location Map URL --}}
                    <div class="col-lg-6 mb-7">
                        <x-metronic.label for="location_map_url" class="col-form-label fw-bold fs-6">
                            {{ __('Location Map URL') }}
                        </x-metronic.label>
                        <x-metronic.input id="location_map_url" type="url" name="location_map_url"
                            placeholder="Enter map URL" :value="old('location_map_url')" />
                    </div>

                    {{-- Start / End Date and Time --}}
                    <div class="col-lg-3 mb-7">
                        <x-metronic.label for="start_date" class="col-form-label fw-bold fs-6">
                            {{ __('Start Date') }}
                        </x-metronic.label>
                        <x-metronic.input id="start_date" type="date" name="start_date"
                            :value="old('start_date')" />
                    </div>
                    <div class="col-lg-3 mb-7">
                        <x-metronic.label for="end_date" class="col-form-label fw-bold fs-6">
                            {{ __('End Date') }}
                        </x-metronic.label>
                        <x-metronic.input id="end_date" type="date" name="end_date"
                            :value="old('end_date')" />
                    </div>
                    <div class="col-lg-3 mb-7">
                        <x-metronic.label for="start_time" class="col-form-label fw-bold fs-6">
                            {{ __('Start Time') }}
                        </x-metronic.label>
                        <x-metronic.input id="start_time" type="time" name="start_time"
                            :value="old('start_time')" />
                    </div>
                    <div class="col-lg-3 mb-7">
                        <x-metronic.label for="end_time" class="col-form-label fw-bold fs-6">
                            {{ __('End Time') }}
                        </x-metronic.label>
                        <x-metronic.input id="end_time" type="time" name="end_time"
                            :value="old('end_time')" />
                    </div>

                    {{-- Venue --}}
                    <div class="col-lg-12 mb-7">
                        <x-metronic.label for="venue" class="col-form-label fw-bold fs-6">
                            {{ __('Venue') }}
                        </x-metronic.label>
                        <x-metronic.textarea id="venue" name="venue"
                            placeholder="Event venue">{{ old('venue') }}</x-metronic.textarea>
                    </div>

                    {{-- Organizer Details --}}
                    <div class="col-lg-4 mb-7">
                        <x-metronic.label for="organizer_name" class="col-form-label fw-bold fs-6">
                            {{ __('Organizer Name') }}
                        </x-metronic.label>
                        <x-metronic.input id="organizer_name" type="text" name="organizer_name"
                            placeholder="Organizer name" :value="old('organizer_name')" />
                    </div>
                    <div class="col-lg-4 mb-7">
                        <x-metronic.label for="organizer_brand" class="col-form-label fw-bold fs-6">
                            {{ __('Organizer Brand') }}
                        </x-metronic.label>
                        <x-metronic.input id="organizer_brand" type="text" name="organizer_brand"
                            placeholder="Organizer brand" :value="old('organizer_brand')" />
                    </div>

                    {{-- Purchase Deadline --}}
                    <div class="col-lg-4 mb-7">
                        <x-metronic.label for="purchase_deadline" class="col-form-label fw-bold fs-6">
                            {{ __('Purchase Deadline') }}
                        </x-metronic.label>
                        <x-metronic.input id="purchase_deadline" type="datetime-local" name="purchase_deadline"
                            :value="old('purchase_deadline')" />
                    </div>

                    {{-- Total Capacity, Age Restriction --}}
                    <div class="col-lg-3 mb-7">
                        <x-metronic.label for="total_capacity" class="col-form-label fw-bold fs-6">
                            {{ __('Total Capacity') }}
                        </x-metronic.label>
                        <x-metronic.input id="total_capacity" type="number" name="total_capacity"
                            :value="old('total_capacity')" />
                    </div>
                    <div class="col-lg-3 mb-7">
                        <x-metronic.label for="age_restriction" class="col-form-label fw-bold fs-6">
                            {{ __('Age Restriction') }}
                        </x-metronic.label>
                        <x-metronic.input id="age_restriction" type="text" name="age_restriction"
                            placeholder="e.g., 18+, All Ages" :value="old('age_restriction')" />
                    </div>

                    {{-- Is Featured --}}
                    <div class="col-lg-3 mb-7">
                        <x-metronic.label for="is_featured" class="col-form-label fw-bold fs-6">
                            {{ __('Featured Event?') }}
                        </x-metronic.label>
                        <x-metronic.select-option id="is_featured" name="is_featured" data-hide-search="true">
                            <option value="0" {{ old('is_featured') == '0' ? 'selected' : '' }}>No</option>
                            <option value="1" {{ old('is_featured') == '1' ? 'selected' : '' }}>Yes</option>
                        </x-metronic.select-option>
                    </div>

                    {{-- Terms & Conditions --}}
                    <div class="col-lg-12 mb-7">
                        <x-metronic.label for="terms_and_conditions" class="col-form-label fw-bold fs-6">
                            {{ __('Terms & Conditions') }}
                        </x-metronic.label>
                        <x-metronic.textarea id="terms_and_conditions" name="terms_and_conditions"
                            placeholder="Enter any terms or conditions">{{ old('terms_and_conditions') }}</x-metronic.textarea>
                    </div>


                </div>

                <div class="text‑end pt‑15">
                    <x-metronic.button type="submit" class="dark rounded-1 px-5">
                        {{ __('Create Event') }}
                    </x-metronic.button>
                </div>
            </form>
        </div>
    </div>
</x-admin-app-layout>
