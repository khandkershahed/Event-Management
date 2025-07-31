<form id="brandForm" method="POST" action="{{ route('admin.brands.store') }}" enctype="multipart/form-data" novalidate>
    @csrf
    <div class="row">
        <div class="col-lg-3 col-md-6 mb-7">
            <x-metronic.label for="category_id"
                class="col-form-label fw-bold fs-6">{{ __('Select a Category') }}</x-metronic.label>
            <x-metronic.select-option id="category_id" name="category_id" data-hide-search="false"
                data-placeholder="Select an option">
                <option></option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">
                        {{ $category->name }}
                    </option>
                @endforeach
            </x-metronic.select-option>
        </div>

        <div class="col-lg-3 col-md-6 mb-7">
            <x-metronic.label for="category_type"
                class="col-form-label fw-bold fs-6">{{ __('Select Category Type') }}</x-metronic.label>
            <x-metronic.select-option id="category_type" name="category_type" data-placeholder="Select an option">
                <option></option>
                <option value="top">Top</option>
                <option value="featured">Featured</option>
                <option value="best_seller">Best Seller</option>
            </x-metronic.select-option>
        </div>

        <div class="col-lg-3 col-md-6 mb-7">
            <x-metronic.label for="country_id"
                class="col-form-label fw-bold fs-6">{{ __('Select Country') }}</x-metronic.label>
            <x-metronic.select-option id="country_id" name="country_id[]" data-hide-search="false" multiple required
                data-placeholder="Select an option">
                <option></option>
                @foreach ($countries as $country)
                    <option value="{{ $country->id }}">
                        {{ $country->name }}
                    </option>
                @endforeach
            </x-metronic.select-option>
        </div>

        <div class="col-lg-3 col-md-6 mb-7">
            <x-metronic.label for="division_id"
                class="col-form-label fw-bold fs-6">{{ __('Select Division') }}</x-metronic.label>
            <x-metronic.select-option id="division_id" name="division_id[]" data-hide-search="false" multiple required
                data-placeholder="Select an option">
                <option></option>
                @foreach ($divisions as $division)
                    <option value="{{ $division->id }}">
                        {{ $division->name }}
                    </option>
                @endforeach
            </x-metronic.select-option>
        </div>

        <div class="col-lg-3 col-md-6 mb-7">
            <x-metronic.label for="city_id"
                class="col-form-label fw-bold fs-6">{{ __('Select City') }}</x-metronic.label>
            <x-metronic.select-option id="city_id" name="city_id[]" data-hide-search="false" multiple
                data-placeholder="Select an option">
                <option></option>
                @foreach ($citys as $city)
                    <option value="{{ $city->id }}">
                        {{ $city->name }}
                    </option>
                @endforeach
            </x-metronic.select-option>
        </div>

        <div class="col-lg-3 col-md-6 mb-7">
            <x-metronic.label for="area_id"
                class="col-form-label fw-bold fs-6">{{ __('Select Area') }}</x-metronic.label>
            <x-metronic.select-option id="area_id" name="area_id[]" data-hide-search="false" multiple
                data-placeholder="Select an option">
                <option></option>
                @foreach ($areas as $area)
                    <option value="{{ $area->id }}">
                        {{ $area->name }}
                    </option>
                @endforeach
            </x-metronic.select-option>
        </div>

        <div class="col-lg-3 col-md-6 mb-7">
            <x-metronic.label for="name" class="col-form-label fw-bold fs-6 required">{{ __('Name') }}
            </x-metronic.label>

            <x-metronic.input id="name" type="text" name="name" :value="old('name')"
                placeholder="Enter the Name" required></x-metronic.input>
        </div>

        <div class="col-lg-3 col-md-6 mb-7">
            <x-metronic.label for="url" class="col-form-label fw-bold fs-6 required">{{ __('Url') }}
            </x-metronic.label>

            <x-metronic.input id="url" type="url" name="url" :value="old('url')"
                placeholder="Enter the Url"></x-metronic.input>
        </div>
        <div class="col-lg-3 col-md-6 mb-7">
            <x-metronic.label for="banner_image" class="col-form-label fw-bold fs-6 ">{{ __('Banner Image') }}
            </x-metronic.label>

            <x-metronic.file-input id="banner_image" :value="old('banner_image')" name="banner_image"></x-metronic.file-input>
        </div>
        <div class="col-lg-3 col-md-6 mb-7">
            <x-metronic.label for="logo" class="col-form-label fw-bold fs-6 ">{{ __('Logo') }}
            </x-metronic.label>

            <x-metronic.file-input id="logo" name="logo" :value="old('logo')"></x-metronic.file-input>
        </div>
        <div class="col-lg-3 col-md-6 mb-7">
            <x-metronic.label for="image" class="col-form-label fw-bold fs-6">{{ __('Thumbnail Image') }}
            </x-metronic.label>

            <x-metronic.file-input id="image" name="image" :value="old('image')"></x-metronic.file-input>
        </div>
        <div class="col-lg-3 col-md-6 mb-7">
            <x-metronic.label for="status" class="col-form-label required fw-bold fs-6">
                {{ __('Select a Status ') }}</x-metronic.label>
            <x-metronic.select-option id="status" name="status" data-hide-search="true"
                data-placeholder="Select an option">
                <option></option>
                <option value="active" @selected(old('status') == 'active')>
                    Active</option>
                <option value="inactive" @selected(old('status') == 'inactive')>
                    Inactive</option>
            </x-metronic.select-option>
        </div>
        <div class="col-lg-6 col-md-12 mb-7">
            <x-metronic.label for="headquarter" class="col-form-label fw-bold fs-6">{{ __('Head Quarter') }}
            </x-metronic.label>
            <x-metronic.input id="headquarter" type="text" name="headquarter" :value="old('headquarter')"
                placeholder="Enter the Head Quater"></x-metronic.input>
        </div>
        <div class="col-lg-12 mb-7">
            <x-metronic.label for="about_title"
                class="col-form-label fw-bold fs-6 required">{{ __('Section One Title') }}
            </x-metronic.label>

            <x-metronic.input id="about_title" type="text" name="about_title" :value="old('about_title')"
                placeholder="Enter the Section Title" required></x-metronic.input>
        </div>

        <div class="col-lg-12 mb-7">
            <x-metronic.label for="about" class="col-form-label fw-bold fs-6 ">{{ __('Section One Description') }}
            </x-metronic.label>

            <textarea id="about" class="ckeditor @error('about')is-invalid @enderror" name="about">{{ old('about') }}</textarea>
            @error('about')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="col-lg-6 col-md-6 mb-7">
            <x-metronic.label for="middle_banner_left"
                class="col-form-label fw-bold fs-6 ">{{ __('Middle Banner Left') }}
            </x-metronic.label>

            <x-metronic.file-input id="middle_banner_left" name="middle_banner_left"
                :value="old('middle_banner_left')"></x-metronic.file-input>
        </div>

        <div class="col-lg-6 col-md-6 mb-7">
            <x-metronic.label for="middle_banner_right"
                class="col-form-label fw-bold fs-6 ">{{ __('Middle Banner Right') }}
            </x-metronic.label>

            <x-metronic.file-input id="middle_banner_right" name="middle_banner_right"
                :value="old('middle_banner_right')"></x-metronic.file-input>
        </div>

        <div class="col-lg-12 mb-7">
            <x-metronic.label for="offer_description_title"
                class="col-form-label fw-bold fs-6 required">{{ __('Section Three Title') }}
            </x-metronic.label>

            <x-metronic.input id="offer_description_title" type="text" name="offer_description_title"
                :value="old('offer_description_title')" placeholder="Enter the Section Three Title" required></x-metronic.input>
        </div>

        <div class="col-lg-12 mb-7">
            <x-metronic.label for="offer_description"
                class="col-form-label fw-bold fs-6 ">{{ __('Section Three Description') }}
            </x-metronic.label>

            <textarea id="offer_description" class="ckeditor @error('offer_description')is-invalid @enderror"
                name="offer_description">{{ old('offer_description') }}</textarea>
            @error('offer_description')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="col-lg-12 mb-7">
            <x-metronic.label for="description_title"
                class="col-form-label fw-bold fs-6 required">{{ __('Section Four Title') }}
            </x-metronic.label>

            <x-metronic.input id="description_title" type="text" name="description_title" :value="old('description_title')"
                placeholder="Enter the Section Four Title" required></x-metronic.input>
        </div>

        <div class="col-lg-12 mb-7">
            <x-metronic.label for="description"
                class="col-form-label fw-bold fs-6">{{ __('Section Four Description') }}
            </x-metronic.label>

            <textarea id="description" class="ckeditor @error('description')is-invalid @enderror" name="description">{{ old('description') }}</textarea>
            @error('description')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        {{--
            <div class="col-lg-4 mb-7">
                <x-metronic.label for="map_url"
                    class="col-form-label fw-bold fs-6">{{ __('Map Url') }}
                </x-metronic.label>

                <x-metronic.textarea id="map_url" type="url" name="map_url"
                    :value="old('map_url')"
                    placeholder="Enter the Map Url"></x-metronic.textarea>
            </div>


            <div class="col-lg-12 mb-7">
                <x-metronic.label for="location"
                    class="col-form-label fw-bold fs-6 ">{{ __('Location') }}
                </x-metronic.label>

                <textarea id="location" class="ckeditor" name="location"></textarea>
            </div>
        --}}

    </div>
    <div class="row mt-2 justify-content-end">
        <div class="d-flex align-items-center justify-content-end">
            <button class="btn btn-lg btn-info rounded-0 tab-trigger-next" type="submit">
                Save & Continue
                <span class="svg-icon svg-icon-4 ms-1 me-0">
                    <i class="fa-solid fa-arrow-right"></i>
                </span>
            </button>
        </div>
    </div>
</form>
