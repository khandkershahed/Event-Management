<x-admin-app-layout :title="'Blog Category'">
    <div class="card card-flash">
        <!--begin::Card header-->
        <div class="card-header mt-6">
            <div class="card-title"></div>

            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Button-->
                <a href="{{ route('admin.blog_category.index') }}" class="btn btn-light-info">
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

            <form method="POST" action="{{ route('admin.blog_category.update', $blogCat->id) }}"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    {{-- Status --}}
                    <div class="col-lg-4 mb-3">
                        <x-metronic.label for="status" class="col-form-label required fw-bold fs-6">
                            {{ __('Select a Status ') }}
                        </x-metronic.label>
                        <x-metronic.select-option id="status" name="status" data-hide-search="true"
                            data-placeholder="Select an option">
                            <option></option>
                            <option value="active" {{ $blogCat->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $blogCat->status == 'inactive' ? 'selected' : '' }}>Inactive
                            </option>
                        </x-metronic.select-option>
                    </div>

                    {{-- Name --}}
                    <div class="col-lg-4 mb-3">
                        <x-metronic.label for="name" class="col-form-label required fw-bold fs-6">
                            {{ __('Name') }}
                        </x-metronic.label>
                        <x-metronic.input id="name" type="text" name="name" placeholder="Enter the name"
                            value="{{ old('name', $blogCat->name) }}"></x-metronic.input>
                    </div>

                    {{-- Short Description --}}
                    <div class="col-lg-12 mb-3">
                        <x-metronic.label for="short_description" class="col-form-label fw-bold fs-6">
                            {{ __('Short Description') }}
                        </x-metronic.label>
                        <x-metronic.textarea id="short_description" name="short_description">
                            {{ old('short_description', $blogCat->short_description) }}
                        </x-metronic.textarea>
                    </div>

                    {{-- Description --}}
                    <div class="col-lg-12 mb-3">
                        <x-metronic.label for="description" class="col-form-label fw-bold fs-6">
                            {{ __('Description') }}
                        </x-metronic.label>
                        <textarea id="description" class="ckeditor" name="description">
                            {{ old('description', $blogCat->description) }}
                        </textarea>
                    </div>

                    {{-- Icon --}}
                    <div class="col-lg-4 mb-3">
                        <x-metronic.label for="logo" class="col-form-label fw-bold fs-6">
                            {{ __('Icon') }}
                        </x-metronic.label>
                        <x-metronic.file-input id="logo" name="logo"></x-metronic.file-input>

                        <img src="{{ !empty($blogCat->logo) ? url('storage/' . $blogCat->logo) : 'No Image' }}"
                            width="80" height="80" alt="" class="mt-3">
                    </div>

                    {{-- Thumbnail Image --}}
                    <div class="col-lg-4 mb-3">
                        <x-metronic.label for="image" class="col-form-label fw-bold fs-6">
                            {{ __('Thumbnail Image') }}
                        </x-metronic.label>
                        <x-metronic.file-input id="image" name="image"></x-metronic.file-input>

                        <img src="{{ !empty($blogCat->image) ? url('storage/' . $blogCat->image) : 'No Image' }}"
                            height="80" width="80" alt="" class="mt-3">
                    </div>

                    {{-- Banner Image --}}
                    <div class="col-lg-4 mb-3">
                        <x-metronic.label for="banner_image" class="col-form-label fw-bold fs-6">
                            {{ __('Banner Image') }}
                        </x-metronic.label>
                        <x-metronic.file-input id="banner_image" name="banner_image"></x-metronic.file-input>

                        @if ($blogCat->banner_image)
                            <img src="{{ !empty($blogCat->banner_image) ? url('storage/' . $blogCat->banner_image) : 'No Image' }}"
                                alt="Banner" class="mt-2" width="80" height="80" class="mt-3">
                        @endif
                    </div>
                </div>

                <div class="text-end pt-15">
                    <x-metronic.button type="submit" class="dark rounded-1 px-5">
                        {{ __('Update Data') }}
                    </x-metronic.button>
                </div>
            </form>

        </div>
    </div>

</x-admin-app-layout>
