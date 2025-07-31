<form class="form" id="brandeditForm" action="{{ route('admin.brands.update', $brand->id) }}" method="POST"
    enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-lg-3 mb-7">
            <x-metronic.label for="category_id"
                class="col-form-label fw-bold fs-6">{{ __('Select a Category') }}</x-metronic.label>
            <x-metronic.select-option id="category_id" name="category_id" data-hide-search="false"
                data-placeholder="Select an option">
                <option></option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ $category->id == old('category_id', $brand->category_id) ? 'selected' : '' }}>
                        {{ $category->name }}</option>
                @endforeach
            </x-metronic.select-option>
        </div>

        <div class="col-lg-3 mb-7">
            <x-metronic.label for="category_type"
                class="col-form-label fw-bold fs-6">{{ __('Select Category Type') }}</x-metronic.label>
            <x-metronic.select-option id="category_type" name="category_type" data-placeholder="Select an option">
                <option>Choose Option</option>
                <option value="top" {{ $brand->category_type == 'top' ? 'selected' : '' }}>Top</option>
                <option value="featured" {{ $brand->category_type == 'featured' ? 'selected' : '' }}>
                    Featured</option>
                <option value="best_seller" {{ $brand->category_type == 'best_seller' ? 'selected' : '' }}>
                    Best Seller</option>
            </x-metronic.select-option>
        </div>
        @php
            // Initialize as empty arrays
            $countryIds = [];
            $divisionIds = [];
            $cityIds = [];
            $areaIds = [];

            // Check and decode if they are strings
            if (is_string($brand->country_id)) {
                $countryIds = json_decode($brand->country_id, true) ?? [];
            } elseif (is_array($brand->country_id)) {
                $countryIds = $brand->country_id;
            }

            if (is_string($brand->division_id)) {
                $divisionIds = json_decode($brand->division_id, true) ?? [];
            } elseif (is_array($brand->division_id)) {
                $divisionIds = $brand->division_id;
            }

            if (is_string($brand->city_id)) {
                $cityIds = json_decode($brand->city_id, true) ?? [];
            } elseif (is_array($brand->city_id)) {
                $cityIds = $brand->city_id;
            }

            if (is_string($brand->area_id)) {
                $areaIds = json_decode($brand->area_id, true) ?? [];
            } elseif (is_array($brand->area_id)) {
                $areaIds = $brand->area_id;
            }
        @endphp

        <div class="col-lg-3 mb-7">
            <x-metronic.label for="country_id"
                class="col-form-label fw-bold fs-6">{{ __('Select Country') }}</x-metronic.label>
            <x-metronic.select-option id="brand_country_id" name="country_id[]" data-hide-search="false" multiple
                data-placeholder="Select an option">
                <option></option>
                @foreach ($countries as $country)
                    <option value="{{ $country->id }}" {{ in_array($country->id, $countryIds) ? 'selected' : '' }}>
                        {{ $country->name }}</option>
                @endforeach
            </x-metronic.select-option>
        </div>

        <!-- Division -->
        <div class="col-lg-3 mb-7">
            <x-metronic.label for="division_id"
                class="col-form-label fw-bold fs-6">{{ __('Select Division') }}</x-metronic.label>
            <x-metronic.select-option id="brand_division_id" name="division_id[]" data-hide-search="false" multiple
                data-placeholder="Select an option">
                <option></option>
                @foreach ($divisions as $division)
                    <option value="{{ $division->id }}" {{ in_array($division->id, $divisionIds) ? 'selected' : '' }}>
                        {{ $division->name }}</option>
                @endforeach
            </x-metronic.select-option>
        </div>

        <!-- City -->
        <div class="col-lg-3 mb-7">
            <x-metronic.label for="city_id"
                class="col-form-label fw-bold fs-6">{{ __('Select City') }}</x-metronic.label>
            <x-metronic.select-option id="brand_city_id" name="city_id[]" data-hide-search="false" multiple
                data-placeholder="Select an option">
                <option></option>
                @foreach ($cities as $city)
                    <option value="{{ $city->id }}" {{ in_array($city->id, $cityIds) ? 'selected' : '' }}>
                        {{ $city->name }}</option>
                @endforeach
            </x-metronic.select-option>
        </div>

        <!-- Area -->
        <div class="col-lg-3 mb-7">
            <x-metronic.label for="area_id"
                class="col-form-label fw-bold fs-6">{{ __('Select Area') }}</x-metronic.label>
            <x-metronic.select-option id="brand_area_id" name="area_id[]" data-hide-search="false" multiple
                data-placeholder="Select an option">
                <option></option>
                @foreach ($areas as $area)
                    <option value="{{ $area->id }}" {{ in_array($area->id, $areaIds) ? 'selected' : '' }}>
                        {{ $area->name }}</option>
                @endforeach
            </x-metronic.select-option>
        </div>

        <div class="col-lg-3 col-md-6 mb-7">
            <x-metronic.label for="name" class="col-form-label fw-bold fs-6 required">{{ __('Name') }}
            </x-metronic.label>
            <x-metronic.input id="name" type="text" name="name" :value="old('name', $brand->name)"
                placeholder="Enter the Name" required></x-metronic.input>
        </div>

        <div class="col-lg-3 col-md-6 mb-7">
            <x-metronic.label for="url" class="col-form-label fw-bold fs-6">{{ __('Url') }}
            </x-metronic.label>

            <x-metronic.input id="url" type="url" name="url" :value="old('url', $brand->url)"
                placeholder="Enter the Url"></x-metronic.input>
        </div>
        <div class="col-lg-3 col-md-6 mb-7">
            <x-metronic.label for="banner_image" class="col-form-label fw-bold fs-6 ">{{ __('Banner Image') }}
            </x-metronic.label>

            <x-metronic.file-input id="banner_image" :value="old('banner_image', $brand->banner_image)" :source="asset('storage/' . $brand->banner_image)"
                name="banner_image"></x-metronic.file-input>
        </div>
        <div class="col-lg-3 col-md-6 mb-7">
            <x-metronic.label for="logo" class="col-form-label fw-bold fs-6">{{ __('Logo') }}
            </x-metronic.label>

            <x-metronic.file-input id="logo" name="logo" :value="old('logo', $brand->logo)"
                :source="asset('storage/' . $brand->logo)"></x-metronic.file-input>
        </div>
        <div class="col-lg-3 col-md-6 mb-7">
            <x-metronic.label for="image" class="col-form-label fw-bold fs-6">{{ __('Thumbnail Image') }}
            </x-metronic.label>

            <x-metronic.file-input id="image" name="image" :value="old('image', $brand->image)"
                :source="asset('storage/' . $brand->image)"></x-metronic.file-input>
        </div>
        <div class="col-lg-3 col-md-6 mb-7">
            <x-metronic.label for="status" class="col-form-label required fw-bold fs-6">
                {{ __('Select a Status ') }}</x-metronic.label>
            <x-metronic.select-option id="status" name="status" data-hide-search="true"
                data-placeholder="Select an option">
                <option></option>
                <option value="active" @selected(old('status', $brand->status) == 'active')>
                    Active</option>
                <option value="inactive" @selected(old('status', $brand->status) == 'inactive')>
                    Inactive</option>
            </x-metronic.select-option>
        </div>
        <div class="col-lg-6 col-md-12 mb-7">
            <x-metronic.label for="headquarter" class="col-form-label fw-bold fs-6">{{ __('Head Quarter') }}
            </x-metronic.label>
            <x-metronic.input id="headquarter" type="text" name="headquarter" :value="old('headquarter', $brand->headquarter)"
                placeholder="Enter the Head Quater"></x-metronic.input>
        </div>
        <div class="col-lg-12 mb-7">
            <x-metronic.label for="about_title" class="col-form-label fw-bold fs-6">{{ __('Section One Title') }}
            </x-metronic.label>

            <x-metronic.input id="about_title" type="text" name="about_title" :value="old('about_title', $brand->about_title)"
                placeholder="Enter the Section Title"></x-metronic.input>
        </div>

        <div class="col-lg-12 mb-7">
            <x-metronic.label for="about" class="col-form-label fw-bold fs-6 ">{{ __('Section One Description') }}
            </x-metronic.label>

            <textarea id="about" class="ckeditor @error('about')is-invalid @enderror" name="about">{{ old('about', $brand->about) }}</textarea>
            @error('about')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="col-lg-6 col-md-6 mb-7">
            <x-metronic.label for="middle_banner_left"
                class="col-form-label fw-bold fs-6 ">{{ __('Middle Banner Left') }}
            </x-metronic.label>

            <x-metronic.file-input id="middle_banner_left" name="middle_banner_left" :value="old('middle_banner_left', $brand->middle_banner_left)"
                :source="asset('storage/' . $brand->middle_banner_left)"></x-metronic.file-input>
        </div>

        <div class="col-lg-6 col-md-6 mb-7">
            <x-metronic.label for="middle_banner_right"
                class="col-form-label fw-bold fs-6 ">{{ __('Middle Banner Right') }}
            </x-metronic.label>

            <x-metronic.file-input id="middle_banner_right" name="middle_banner_right" :value="old('middle_banner_right', $brand->middle_banner_right)"
                :source="asset('storage/' . $brand->middle_banner_right)"></x-metronic.file-input>
        </div>

        <div class="col-lg-12 mb-7">
            <x-metronic.label for="offer_description_title"
                class="col-form-label fw-bold fs-6">{{ __('Section Three Title') }}
            </x-metronic.label>

            <x-metronic.input id="offer_description_title" type="text" name="offer_description_title"
                :value="old('offer_description_title', $brand->offer_description_title)" placeholder="Enter the Section Three Title"></x-metronic.input>
        </div>

        <div class="col-lg-12 mb-7">
            <x-metronic.label for="offer_description"
                class="col-form-label fw-bold fs-6 ">{{ __('Section Three Description') }}
            </x-metronic.label>

            <textarea id="offer_description" class="ckeditor @error('offer_description')is-invalid @enderror"
                name="offer_description">{{ old('offer_description', $brand->offer_description) }}</textarea>
            @error('offer_description')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="col-lg-12 mb-7">
            <x-metronic.label for="description_title"
                class="col-form-label fw-bold fs-6">{{ __('Section Four Title') }}
            </x-metronic.label>

            <x-metronic.input id="description_title" type="text" name="description_title" :value="old('description_title', $brand->description_title)"
                placeholder="Enter the Section Four Title"></x-metronic.input>
        </div>

        <div class="col-lg-12 mb-7">
            <x-metronic.label for="description"
                class="col-form-label fw-bold fs-6">{{ __('Section Four Description') }}
            </x-metronic.label>

            <textarea id="description" class="ckeditor @error('description') is-invalid @enderror" name="description">
                {{ old('description', $brand->description) }}
            </textarea>
            @error('description')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

    </div>
    <div class="row mt-2 justify-content-end">
        <div class="d-flex align-items-center justify-content-end">
            <a class="btn btn-lg btn-info rounded-0 tab-trigger-brand-next" data-bs-toggle="tab"
                data-bs-target="#kt_vtab_pane_2" aria-selected="false" role="tab" tabindex="-1">
                Save & Continue
                <span class="svg-icon svg-icon-4 ms-1 me-0">
                    <i class="fa-solid fa-arrow-right"></i>
                </span>
            </a>
        </div>
    </div>
</form>

@push('scripts')
    <script>
        // Initialize Select2 (or any other select plugin you're using)
        $(document).ready(function() {
            $('#category_id').select2({
                placeholder: "Select an option",
                allowClear: true
            });

            $('#category_type').select2({
                placeholder: "Select an option",
                allowClear: true
            });

            $('#brand_country_id').select2({
                placeholder: "Select an option",
                allowClear: true
            });

            $('#brand_division_id').select2({
                placeholder: "Select an option",
                allowClear: true
            });

            $('#brand_city_id').select2({
                placeholder: "Select an option",
                allowClear: true
            });

            $('#brand_area_id').select2({
                placeholder: "Select an option",
                allowClear: true
            });

            $('#status').select2({
                placeholder: "Select an option",
                allowClear: true
            });
        });
    </script>
@endpush
