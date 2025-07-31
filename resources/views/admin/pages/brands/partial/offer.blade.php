<div class="row">
    <div class="col-lg-12">
        <!--begin::Repeater-->
        <div id="offers">
            <!--begin::Form group-->
            <div class="form-group">
                <div data-repeater-list="offers">
                    <div data-repeater-item>
                        <div class="row align-items-center">
                            <div class="col-lg-11" style="border: 1px solid #eee;">
                                <div class="form-group row mb-5">
                                    <div class="col-lg-3">
                                        <div class="mb-5">
                                            <x-metronic.label for="notify_to"
                                                class="col-form-label fw-bold fs-6">{{ __('Select a Notify') }}</x-metronic.label>
                                            <select class="form-select" data-kt-repeater="repeater-select" name="notify_to[]"
                                                data-hide-search="false" data-placeholder="Select an option">
                                                <option></option>
                                                @foreach ($admins as $admin)
                                                    <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="mb-5">
                                            <x-metronic.label for="country_id"
                                                class="col-form-label fw-bold fs-6">{{ __('Select Country') }}</x-metronic.label>
                                            <select class="form-select" data-kt-repeater="repeater-select" name="country_id[]"
                                                data-hide-search="false" data-placeholder="Select an option">
                                                <option></option>
                                                @foreach ($countries as $country)
                                                    <option value="{{ $country->id }}" @selected(old('country_id') == $country->id)>
                                                        {{ $country->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <div class="mb-5">
                                            <x-metronic.label for="division_id"
                                                class="col-form-label fw-bold fs-6">{{ __('Select Division') }}</x-metronic.label>
                                            <select class="form-select" data-kt-repeater="repeater-select" name="division_id[]"
                                                data-hide-search="false" data-placeholder="Select an option">
                                                <option></option>
                                                @foreach ($divisions as $division)
                                                    <option value="{{ $division->id }}" @selected(old('division_id') == $division->id)>
                                                        {{ $division->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <div class="mb-5">
                                            <x-metronic.label for="city_id"
                                                class="col-form-label fw-bold fs-6">{{ __('Select City') }}</x-metronic.label>
                                            <select class="form-select" data-kt-repeater="repeater-select" name="city_id[]"
                                                data-hide-search="false" data-placeholder="Select an option">
                                                <option></option>
                                                @foreach ($citys as $city)
                                                    <option value="{{ $city->id }}" @selected(old('city_id') == $city->id)>
                                                        {{ $city->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 mb-5">
                                        <x-metronic.label for="area_id"
                                            class="col-form-label fw-bold fs-6">{{ __('Select Area') }}</x-metronic.label>
                                        <select class="form-select" data-kt-repeater="repeater-select" name="area_id[]"
                                            data-hide-search="false" data-placeholder="Select an option">
                                            <option></option>
                                            @foreach ($areas as $area)
                                                <option value="{{ $area->id }}" @selected(old('area_id') == $area->id)>
                                                    {{ $area->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-lg-3 mb-5">
                                        <x-metronic.label for="name"
                                            class="col-form-label fw-bold fs-6">{{ __('name') }}
                                        </x-metronic.label>
                                        <x-metronic.input id="name" type="text" name="name" :value="old('name')"
                                            placeholder="Enter the Name"></x-metronic.input>
                                    </div>


                                    <div class="col-lg-3 mb-7">
                                        <x-metronic.label for="url"
                                            class="col-form-label fw-bold fs-6">{{ __('Url') }}
                                        </x-metronic.label>

                                        <x-metronic.input id="url" type="url" name="url" :value="old('url')"
                                            placeholder="https://www.google.com"></x-metronic.input>
                                    </div>

                                    <div class="col-lg-3 mb-7">
                                        <x-metronic.label for="source_url"
                                            class="col-form-label fw-bold fs-6">{{ __('Source Url') }}
                                        </x-metronic.label>

                                        <x-metronic.input id="url" type="url" name="source_url"
                                            :value="old('source_url')" placeholder="https://www.google.com"></x-metronic.input>
                                    </div>

                                    <div class="col-lg-3 mb-7">
                                        <x-metronic.label for="badge"
                                            class="col-form-label fw-bold fs-6">{{ __('Badge') }}
                                        </x-metronic.label>

                                        <x-metronic.input id="badge" type="text" name="badge" :value="old('badge')"
                                            placeholder="Eg: 30"></x-metronic.input>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="mb-7">
                                            <x-metronic.label for="tags"
                                                class="col-form-label fw-bold fs-6">{{ __('Tags') }}
                                            </x-metronic.label>
                                            <input class="form-control" data-kt-repeater="tagify" name="tags"
                                                value="{{ old('tags') }}" />
                                        </div>
                                    </div>

                                    <div class="col-lg-3 mb-7">
                                        <x-metronic.label for="price"
                                            class="col-form-label fw-bold fs-6">{{ __('Price') }}
                                        </x-metronic.label>

                                        <x-metronic.input id="price" type="number" name="price" :value="old('price')"
                                            placeholder="Eg:10,000.00"></x-metronic.input>
                                    </div>

                                    <div class="col-lg-3 mb-7">
                                        <x-metronic.label for="offer_price"
                                            class="col-form-label fw-bold fs-6">{{ __('Offer Price') }}
                                        </x-metronic.label>

                                        <x-metronic.input id="offer_price" type="number" name="offer_price"
                                            :value="old('offer_price')" placeholder="Eg:10,000.00"></x-metronic.input>
                                    </div>

                                    <div class="col-lg-3 mb-7">
                                        <x-metronic.label for="offer_price"
                                            class="col-form-label fw-bold fs-6">{{ __('Coupon Code') }}
                                        </x-metronic.label>

                                        <x-metronic.input id="coupon_code" type="text" name="coupon_code"
                                            :value="old('coupon_code')" placeholder="Eg: Dis-125874"></x-metronic.input>
                                    </div>

                                    <!-- Update for date fields -->
                                    <div class="col-lg-3 mb-7">
                                        <x-metronic.label for="start_date"
                                            class="col-form-label fw-bold fs-6">{{ __('Start Date') }}</x-metronic.label>
                                        <x-metronic.input id="start_date" type="datetime-local" name="start_date"
                                            :value="old('start_date')"></x-metronic.input>
                                    </div>

                                    <div class="col-lg-3 mb-7">
                                        <x-metronic.label for="start_date"
                                            class="col-form-label fw-bold fs-6">{{ __('Notification Date') }}
                                        </x-metronic.label>

                                        <x-metronic.input id="notification_date" type="datetime-local"
                                            name="notification_date" :value="old('notification_date')"
                                            placeholder="Enter the Coupon Code"></x-metronic.input>
                                    </div>

                                    <div class="col-lg-3 mb-7">
                                        <x-metronic.label for="start_date"
                                            class="col-form-label fw-bold fs-6">{{ __('Expiry Date') }}
                                        </x-metronic.label>

                                        <x-metronic.input id="expiry_date" type="datetime-local" name="expiry_date"
                                            :value="old('expiry_date')" placeholder="Enter the Expiry Code"></x-metronic.input>
                                    </div>


                                    <div class="col-lg-6 mb-7">
                                        <x-metronic.label for="image"
                                            class="col-form-label fw-bold fs-6">{{ __('Thumbnail Image') }}
                                        </x-metronic.label>
                                        <x-metronic.file-input id="image" name="image"
                                            :value="old('image')"></x-metronic.file-input>
                                    </div>


                                    <div class="col-lg-6 mb-7">
                                        <x-metronic.label for="status" class="col-form-label fw-bold fs-6">
                                            {{ __('Select a Status ') }}</x-metronic.label>
                                        <x-metronic.select-option id="status" name="status"
                                            data-hide-search="true" data-placeholder="Select an option">
                                            <option></option>
                                            <option value="active" @selected(old('status') == 'active')>Active</option>
                                            <option value="inactive" @selected(old('status') == 'inactive')>Inactive</option>
                                        </x-metronic.select-option>
                                    </div>
                                    <div class="col-lg-4 mb-7">
                                        <x-metronic.label for="map_url"
                                            class="col-form-label fw-bold fs-6">{{ __('Map Url') }}
                                        </x-metronic.label>

                                        <x-metronic.textarea id="map_url" type="text" name="map_url"
                                            :value="old('map_url')" placeholder="Please Map Url"></x-metronic.textarea>
                                    </div>
                                    <div class="col-lg-8 mb-7">
                                        <x-metronic.label for="short_description"
                                            class="col-form-label fw-bold fs-6">{{ __('Short Description') }}
                                        </x-metronic.label>
                                        <x-metronic.textarea id="short_description"
                                            placeholder="Write Short Description"
                                            name="short_description"></x-metronic.textarea>
                                    </div>
                                    <div class="col-lg-12 mb-7">
                                        <x-metronic.label for="description"
                                            class="col-form-label fw-bold fs-6">{{ __('Description') }}
                                        </x-metronic.label>
                                        <textarea id="description" data-kt-repeater="ckeditor" class="" name="description"></textarea>
                                    </div>
                                    <div class="col-lg-12 mb-7">
                                        <x-metronic.label for="locations"
                                            class="col-form-label fw-bold fs-6">{{ __('Description Two') }}
                                        </x-metronic.label>
                                        <textarea id="locations" data-kt-repeater="ckeditor" class="" name="locations"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-1">
                                <a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger mt-7">
                                    <i class="la la-trash-o fs-3"></i>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!--end::Form group-->

            <!--begin::Form group-->
            <div class="form-group mt-5">
                <a href="javascript:;" data-repeater-create class="btn btn-primary">
                    <i class="la la-plus fs-3"></i>
                </a>
            </div>
            <!--end::Form group-->
        </div>
        <!--end::Repeater-->
    </div>
</div>
<div class="row mt-2 justify-content-end">
    <div class="d-flex align-items-center justify-content-between">
        <a class="btn btn-lg btn-info rounded-0 tab-trigger-previous" data-bs-target="#kt_vtab_pane_1"
            aria-selected="false" role="tab" tabindex="-1">
            Previous
            <span class="svg-icon svg-icon-4 ms-1 me-0">
                <i class="fa-solid fa-arrow-right"></i>
            </span>
        </a>
        <x-metronic.button type="submit" class="info rounded-0">
            {{ __('Submit') }}
            <span class="svg-icon svg-icon-4 ms-1 me-0">
                <i class="fa-solid fa-arrow-right"></i>
            </span>
        </x-metronic.button>
    </div>
</div>

@push('scripts')
    <script>
        $('#offers').repeater({
            initEmpty: false,

            // defaultValues: {
            //     'text-input': 'foo'
            // },

            show: function() {
                $(this).slideDown();

                // Re-init repeater-select
                $(this).find('[data-kt-repeater="repeater-select"]').select2();

                    // Re-init tagify
                    new Tagify(this.querySelector('[data-kt-repeater="tagify"]'));
                document.querySelectorAll('[data-kt-repeater="ckeditor"]').forEach(element => {
                    if (!element.classList.contains('ck-editor__editable_inline')) {
                        ClassicEditor
                            .create(element)
                            .then(editor => {
                                console.log('CKEditor initialized:', editor);
                            })
                            .catch(error => {
                                console.error('CKEditor initialization error:', error);
                            });
                    }
                });
            },

            hide: function(deleteElement) {
                $(this).slideUp(deleteElement);
            },

            ready: function() {
                // Init select2
                $('[data-kt-repeater="repeater-select"]').select2();

                // Init Tagify
                new Tagify(document.querySelector('[data-kt-repeater="tagify"]'));

                document.querySelectorAll('[data-kt-repeater="ckeditor"]').forEach(element => {
                    if (!element.classList.contains('ck-editor__editable_inline')) {
                        ClassicEditor
                            .create(element)
                            .then(editor => {
                                console.log('CKEditor initialized:', editor);
                            })
                            .catch(error => {
                                console.error('CKEditor initialization error:', error);
                            });
                    }
                });
            }
        });
    </script>
@endpush
