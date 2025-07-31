<x-admin-app-layout :title="'Coupon Add'">
    <div class="card card-flash">
        <!--begin::Card header-->
        <div class="card-header mt-6">
            <div class="card-title"></div>

            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Button-->
                <a href="{{ route('admin.coupon.index') }}" class="btn btn-light-info">
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

            <form method="POST" action="{{ route('admin.coupon.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <!-- Status -->
                    <div class="col-lg-3 mb-3">
                        <x-metronic.label for="status" class="col-form-label required fw-bold fs-6">
                            {{ __('Select a Status') }}
                        </x-metronic.label>
                        <x-metronic.select-option id="status" name="status" data-hide-search="true" data-placeholder="Select an option">
                            <option></option>
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </x-metronic.select-option>
                        @error('status')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Name -->
                    <div class="col-lg-3 mb-3">
                        <x-metronic.label for="name" class="col-form-label required fw-bold fs-6">
                            {{ __('Name') }}
                        </x-metronic.label>
                        <x-metronic.input id="name" type="text" name="name" placeholder="Enter the name" value="{{ old('name') }}"></x-metronic.input>

                    </div>

                    <!-- Badge -->
                    <div class="col-lg-3 mb-3">
                        <x-metronic.label for="badge" class="col-form-label required fw-bold fs-6">
                            {{ __('Badge') }}
                        </x-metronic.label>
                        <x-metronic.input id="badge" type="text" name="badge" placeholder="Enter the Badge" value="{{ old('badge') }}"></x-metronic.input>

                    </div>

                    <!-- Coupon Code -->
                    <div class="col-lg-3 mb-3">
                        <x-metronic.label for="coupon_code" class="col-form-label required fw-bold fs-6">
                            {{ __('Coupon Code') }}
                        </x-metronic.label>
                        <x-metronic.input id="coupon_code" type="text" name="coupon_code" placeholder="Enter coupon code" value="{{ old('coupon_code') }}"></x-metronic.input>

                    </div>

                    <!-- Price -->
                    <div class="col-lg-2 mb-3">
                        <x-metronic.label for="price" class="col-form-label fw-bold fs-6">
                            {{ __('Price') }}
                        </x-metronic.label>
                        <x-metronic.input id="price" type="number" step="0.01" name="price" placeholder="Enter price" value="{{ old('price') }}"></x-metronic.input>

                    </div>

                    <!-- Offer Price -->
                    <div class="col-lg-2 mb-3">
                        <x-metronic.label for="offer_price" class="col-form-label fw-bold fs-6">
                            {{ __('Offer Price') }}
                        </x-metronic.label>
                        <x-metronic.input id="offer_price" type="number" step="0.01" name="offer_price" placeholder="Enter offer price" value="{{ old('offer_price') }}"></x-metronic.input>

                    </div>

                    <!-- Start Date -->
                    <div class="col-lg-3 mb-3">
                        <x-metronic.label for="start_date" class="col-form-label fw-bold fs-6">
                            {{ __('Start Date') }}
                        </x-metronic.label>
                        <x-metronic.input id="start_date" type="datetime-local" name="start_date" value="{{ old('start_date') }}"></x-metronic.input>

                    </div>

                    <!-- Expiry Date -->
                    <div class="col-lg-3 mb-3">
                        <x-metronic.label for="expiry_date" class="col-form-label fw-bold fs-6">
                            {{ __('Expiry Date') }}
                        </x-metronic.label>
                        <x-metronic.input id="expiry_date" type="datetime-local" name="expiry_date" value="{{ old('expiry_date') }}"></x-metronic.input>
                       
                    </div>

                    <!-- Notification Date -->
                    <div class="col-lg-2 mb-3">
                        <x-metronic.label for="notification_date" class="col-form-label fw-bold fs-6">
                            {{ __('Notification Date') }}
                        </x-metronic.label>
                        <x-metronic.input id="notification_date" type="datetime-local" name="notification_date" value="{{ old('notification_date') }}"></x-metronic.input>
                        @error('notification_date')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Locations -->
                    <div class="col-lg-6 mb-3">
                        <x-metronic.label for="locations" class="col-form-label fw-bold fs-6">
                            {{ __('Locations') }}
                        </x-metronic.label>
                        <textarea id="locations" class="form-control" cols="1" rows="1" name="locations">{{ old('locations') }}</textarea>
                        @error('locations')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- URL -->
                    <div class="col-lg-6 mb-3">
                        <x-metronic.label for="url" class="col-form-label fw-bold fs-6">
                            {{ __('Url') }}
                        </x-metronic.label>
                        <textarea id="url" class="form-control" cols="1" rows="1" name="url">{{ old('url') }}</textarea>
                        @error('url')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Source URL -->
                    <div class="col-lg-6 mb-3">
                        <x-metronic.label for="source_url" class="col-form-label fw-bold fs-6">
                            {{ __('Source Url') }}
                        </x-metronic.label>
                        <textarea id="source_url" class="form-control" cols="1" rows="1" name="source_url">{{ old('source_url') }}</textarea>
                        @error('source_url')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Map URL -->
                    <div class="col-lg-6 mb-3">
                        <x-metronic.label for="map_url" class="col-form-label fw-bold fs-6">
                            {{ __('Map Url') }}
                        </x-metronic.label>
                        <textarea id="map_url" class="form-control" cols="1" rows="1" name="map_url">{{ old('map_url') }}</textarea>
                        @error('map_url')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="col-lg-12 mb-3">
                        <x-metronic.label for="description" class="col-form-label fw-bold fs-6">
                            {{ __('Description') }}
                        </x-metronic.label>
                        <textarea id="description" class="ckeditor" name="description">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="text-end pt-15">
                        <x-metronic.button type="submit" class="dark rounded-1 px-5">
                            {{ __('Submit') }}
                        </x-metronic.button>
                    </div>
                </div>
            </form>


        </div>
    </div>

</x-admin-app-layout>
