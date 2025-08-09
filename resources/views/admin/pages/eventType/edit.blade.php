<x-admin-app-layout :title="'Edit Event Type'">
    <div class="card card-flash">
        <div class="card-header mt-6">
            <div class="card-title"></div>
            <div class="card-toolbar">
                <a href="{{ route('admin.event-type.index') }}" class="btn btn-light-info">
                    <span class="svg-icon svg-icon-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5" fill="currentColor" />
                            <rect x="10.8891" y="17.8033" width="12" height="2" rx="1"
                                transform="rotate(-90 10.8891 17.8033)" fill="currentColor" />
                            <rect x="6.01041" y="10.9247" width="12" height="2" rx="1" fill="currentColor" />
                        </svg>
                    </span>
                    Back to the list
                </a>
            </div>
        </div>
        <div class="card-body pt-0">
            <form method="POST" action="{{ route('admin.event-type.update', $event_type->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    {{-- Name --}}
                    <div class="col-lg-6 mb-7">
                        <x-metronic.label for="name" class="col-form-label required fw-bold fs-6">
                            {{ __('Event Type Name') }}
                        </x-metronic.label>
                        <x-metronic.input id="name" type="text" name="name"
                            placeholder="Enter the Event Type name"
                            :value="old('name', $event_type->name)" required />
                    </div>

                    {{-- Code --}}
                    <div class="col-lg-3 mb-7">
                        <x-metronic.label for="code" class="col-form-label fw-bold fs-6">
                            {{ __('Code') }}
                        </x-metronic.label>
                        <x-metronic.input id="code" type="text" name="code"
                            placeholder="Enter code (optional)"
                            :value="old('code', $event_type->code)" />
                    </div>

                    {{-- Serial --}}
                    <div class="col-lg-3 mb-7">
                        <x-metronic.label for="serial" class="col-form-label fw-bold fs-6">
                            {{ __('Serial') }}
                        </x-metronic.label>
                        <x-metronic.input id="serial" type="text" name="serial"
                            placeholder="Enter serial (optional)"
                            :value="old('serial', $event_type->serial)" />
                    </div>

                    {{-- Status --}}
                    <div class="col-lg-3 mb-7">
                        <x-metronic.label for="status" class="col-form-label required fw-bold fs-6">
                            {{ __('Select Status') }}
                        </x-metronic.label>
                        <x-metronic.select-option id="status" name="status" data-hide-search="true"
                            data-placeholder="Select an option" required>
                            <option value="active" {{ old('status', $event_type->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $event_type->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </x-metronic.select-option>
                    </div>

                    {{-- Description --}}
                    <div class="col-lg-9 mb-7">
                        <x-metronic.label for="description" class="col-form-label fw-bold fs-6">
                            {{ __('Description') }}
                        </x-metronic.label>
                        <x-metronic.textarea id="description" name="description"
                            placeholder="Enter description (optional)">{{ old('description', $event_type->description) }}</x-metronic.textarea>
                    </div>

                    {{-- Logo --}}
                    <div class="col-lg-4 mb-7">
                        <x-metronic.label for="logo" class="col-form-label fw-bold fs-6">
                            {{ __('Icon (Logo)') }}
                        </x-metronic.label>
                        <x-metronic.file-input id="logo" name="logo" :source="asset('storage/' . $event_type->logo)"/>
                    </div>

                    {{-- Image --}}
                    <div class="col-lg-4 mb-7">
                        <x-metronic.label for="image" class="col-form-label fw-bold fs-6">
                            {{ __('Thumbnail Image') }}
                        </x-metronic.label>
                        <x-metronic.file-input id="image" name="image" :value="old('image')" :source="asset('storage/' . $event_type->image)"/>
                    </div>

                    {{-- Banner Image --}}
                    <div class="col-lg-4 mb-7">
                        <x-metronic.label for="banner_image" class="col-form-label fw-bold fs-6">
                            {{ __('Banner Image') }}
                        </x-metronic.label>
                        <x-metronic.file-input id="banner_image" name="banner_image" :source="asset('storage/' . $event_type->banner_image)"/>
                    </div>

                </div>

                <div class="text-end pt-15">
                    <x-metronic.button type="submit"
                        class="dark rounded-1 px-5">{{ __('Update') }}</x-metronic.button>
                </div>
            </form>
        </div>
    </div>
</x-admin-app-layout>
