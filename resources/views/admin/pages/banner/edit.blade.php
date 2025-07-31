<x-admin-app-layout :title="'Banner Edit'">
    <div class="card card-flash">
        <!--begin::Card header-->
        <div class="card-header mt-6">
            <div class="card-title"></div>

            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Button-->
                <a href="{{ route('admin.banner.index') }}" class="btn btn-light-info">
                    <!--begin::Svg Icon | path: brands/duotune/general/gen035.svg-->
                    <span class="svg-icon svg-icon-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none">
                            <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5"
                                fill="currentColor" />
                            <rect x="10.8891" y="17.8033" width="12" height="2" rx="1"
                                transform="rotate(-90 10.8891 17.8033)" fill="currentColor" />
                            <rect x="6.01041" y="10.9247" width="12" height="2" rx="1"
                                fill="currentColor" />
                        </svg>
                    </span>
                    Back to the list
                </a>
            </div>
        </div>
        <div class="card-body pt-0">

            <form method="POST" action="{{ route('admin.banner.update', $banner->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Status -->
                    <div class="col-lg-4 mb-3">
                        <x-metronic.label for="status" class="col-form-label required fw-bold fs-6">
                            {{ __('Select a Status') }}
                        </x-metronic.label>
                        <x-metronic.select-option id="status" name="status" data-hide-search="true"
                            data-placeholder="Select an option">
                            <option></option>
                            <option value="active" {{ old('status', $banner->status) == 'active' ? 'selected' : '' }}>
                                Active</option>
                            <option value="inactive"
                                {{ old('status', $banner->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </x-metronic.select-option>
                    </div>

                    <!-- Badge -->
                    <div class="col-lg-4 mb-3">
                        <x-metronic.label for="badge" class="col-form-label fw-bold fs-6">
                            {{ __('Badge') }}
                        </x-metronic.label>
                        <x-metronic.input id="badge" type="text" name="badge" placeholder="Enter the badge"
                            value="{{ old('badge', $banner->badge) }}"></x-metronic.input>
                    </div>

                    <!-- Banner Name -->
                    <div class="col-lg-4 mb-3">
                        <x-metronic.label for="name" class="col-form-label required fw-bold fs-6">
                            {{ __('Banner Name') }}
                        </x-metronic.label>
                        <x-metronic.input id="name" type="text" name="name" placeholder="Enter the name"
                            value="{{ old('name', $banner->name) }}"></x-metronic.input>
                    </div>

                    <!-- Banner URL -->
                    <div class="col-lg-6 mb-3">
                        <x-metronic.label for="url" class="col-form-label fw-bold fs-6">
                            {{ __('Banner URL') }}
                        </x-metronic.label>
                        <x-metronic.input id="url" type="text" name="url" placeholder="Enter the URL"
                            value="{{ old('url', $banner->url) }}"></x-metronic.input>
                    </div>

                    <!-- Button Name -->
                    <div class="col-lg-6 mb-3">
                        <x-metronic.label for="button_name" class="col-form-label fw-bold fs-6">
                            {{ __('Button Name') }}
                        </x-metronic.label>
                        <x-metronic.input id="button_name" type="text" name="button_name"
                            placeholder="Enter the Button Name"
                            value="{{ old('button_name', $banner->button_name) }}"></x-metronic.input>
                    </div>

                    <!-- Thumbnail Image -->
                    <div class="col-lg-4 mb-3">
                        <x-metronic.label for="image" class="col-form-label fw-bold fs-6">
                            {{ __('Thumbnail Image') }}
                        </x-metronic.label>
                        <x-metronic.file-input id="image" name="image"></x-metronic.file-input>

                        @if ($banner->image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $banner->image) }}" alt="Banner Image"
                                    class="img-thumbnail" width="100">
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="text-end pt-15">
                    <x-metronic.button type="submit" class="dark rounded-1 px-5">
                        {{ __('Update Data') }}
                    </x-metronic.button>
                </div>
            </form>


        </div>
    </div>

</x-admin-app-layout>
