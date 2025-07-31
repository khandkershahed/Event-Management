<x-admin-app-layout :title="'Terms and Condition Edit'">
    <div class="card card-flash">
        <div class="card-header py-3">
            <div class="card-title"></div>
            <div class="card-toolbar">
                <a href="{{ route('admin.terms-condition.index') }}" class="btn btn-light-info">
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

            <form class="form" action="{{ route('admin.terms-condition.update', $term->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-lg-8 mb-7 offset-lg-2">
                        <x-metronic.label for="title"
                            class="col-form-label fw-bold fs-6 required">{{ __('Title') }}
                        </x-metronic.label>

                        <x-metronic.input id="title" type="text" name="title" :value="$term->title"
                            placeholder="Enter the Title" required></x-metronic.input>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 mb-lg-7 mb-3">
                        <x-metronic.label for="effective_date"
                            class="col-form-label fw-bold fs-6 required">{{ __('Effective Date') }}
                        </x-metronic.label>

                        <input type="date" name="effective_date"
                            class="form-control @error('effective_date') is-invalid @enderror"
                            placeholder="Effective Date" min="{{ Carbon\Carbon::now()->format('Y-m-d') }}"
                            value="{{ $term->effective_date }}">

                        @error('effective_date')
                            <span class="text-danger"> {{ $message }} </span>
                        @enderror
                    </div>
                    <div class="col-lg-3 mb-lg-7 mb-3">
                        <x-metronic.label for="expiration_date"
                            class="col-form-label fw-bold fs-6 required">{{ __('Expiration Date') }}
                        </x-metronic.label>

                        <input type="date" name="expiration_date"
                            class="form-control @error('expiration_date') is-invalid @enderror"
                            placeholder="Expiration Date" min="{{ Carbon\Carbon::now()->format('Y-m-d') }}"
                            value="{{ $term->expiration_date }}">

                        @error('expiration_date')
                            <span class="text-danger"> {{ $message }} </span>
                        @enderror
                    </div>

                    <div class="col-lg-3 mb-lg-7 mb-3">
                        <x-metronic.label for="version" class="col-form-label fw-bold fs-6">{{ __('Version') }}
                        </x-metronic.label>

                        <x-metronic.input id="version" type="text" name="version" :value="old('version', $term->version)"
                            placeholder="Enter the version"></x-metronic.input>
                    </div>

                    <div class="col-lg-3 mb-lg-7 mb-3">
                        <x-metronic.label for="status" class="col-form-label required fw-bold fs-6">
                            {{ __('Select a Status ') }}</x-metronic.label>
                        <x-metronic.select-option id="status" name="status" data-hide-search="true"
                            data-placeholder="Select an option">
                            <option></option>
                            <option value="active" @selected($term->status == 'active')>Active</option>
                            <option value="inactive" @selected($term->status == 'inactive')>Inactive</option>
                        </x-metronic.select-option>
                    </div>
                </div>
                <div class="row">
                    <x-metronic.label for="content" class="col-form-label required fw-bold fs-6">
                        {{ __('Content') }}</x-metronic.label>
                    <textarea rows="3" cols="3" name="content" class="ckeditor w-100 @error('content') is-invalid @enderror">{!! $term->content !!}</textarea>
                    @error('content')
                        <span class="text-danger"> {{ $message }} </span>
                    @enderror
                </div>
                <div class="text-center pt-15">
                    <x-metronic.button type="submit" class="primary">
                        {{ __('Submit') }}
                    </x-metronic.button>
                </div>

            </form>

        </div>
    </div>
</x-admin-app-layout>
