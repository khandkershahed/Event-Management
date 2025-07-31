<x-admin-app-layout :title="'Blog'">
    <div class="card card-flash">
        <!--begin::Card header-->
        <div class="card-header mt-6">
            <div class="card-title"></div>

            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Button-->
                <a href="{{ route('admin.blog.index') }}" class="btn btn-light-info">
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

            <form method="POST" action="{{ route('admin.blog.update', $blog->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">

                    <!-- Status -->
                    <div class="col-lg-2 mb-3">
                        <x-metronic.label for="status" class="col-form-label required fw-bold fs-6">
                            {{ __('Select a Status') }}
                        </x-metronic.label>
                        <x-metronic.select-option id="status" name="status" data-hide-search="true"
                            data-placeholder="Select an option">
                            <option></option>
                            <option value="active" {{ old('status', $blog->status) == 'active' ? 'selected' : '' }}>
                                Active</option>
                            <option value="inactive" {{ old('status', $blog->status) == 'inactive' ? 'selected' : '' }}>
                                Inactive</option>
                        </x-metronic.select-option>
                        @error('status')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Blog Category -->
                    <div class="col-lg-3 mb-3">
                        <x-metronic.label for="blog_category_id" class="col-form-label required fw-bold fs-6">
                            {{ __('Blog Category') }}
                        </x-metronic.label>
                        <x-metronic.select-option id="blog_category_id" name="blog_category_id"
                            data-placeholder="Select Category">
                            <option></option>
                            @foreach ($blogCats as $blogCat)
                                <option value="{{ $blogCat->id }}"
                                    {{ old('blog_category_id', $blog->blog_category_id) == $blogCat->id ? 'selected' : '' }}>
                                    {{ $blogCat->name }}
                                </option>
                            @endforeach
                        </x-metronic.select-option>
                    </div>

                    <!-- Name -->
                    <div class="col-lg-4 mb-3">
                        <x-metronic.label for="name" class="col-form-label required fw-bold fs-6">
                            {{ __('Name') }}
                        </x-metronic.label>
                        <x-metronic.input id="name" type="text" name="name" placeholder="Enter the name"
                            :value="old('name', $blog->name)"></x-metronic.input>
                    </div>

                    {{-- Date --}}
                    <div class="col-lg-3 mb-3">
                        <x-metronic.label for="date" class="col-form-label fw-bold fs-6">
                            {{ __('Date') }}
                        </x-metronic.label>
                        <x-metronic.input id="date" type="date" name="date"
                            :value="old('date', $blog->date)"></x-metronic.input>
                    </div>

                    <!-- Image -->
                    <div class="col-lg-3 mb-3">
                        <x-metronic.label for="image" class="col-form-label fw-bold fs-6">
                            {{ __('Thumbnail Image') }}
                        </x-metronic.label>
                        <x-metronic.file-input id="image" name="image"></x-metronic.file-input>
                        @error('image')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                        <img src="{{ !empty($blog->image) ? url('storage/' . $blog->image) : 'No Image' }}"
                            height="70" width="70" alt="" class="mt-3">
                    </div>

                    <!-- Short Description -->
                    <div class="col-lg-9 mb-3">
                        <x-metronic.label for="short_description" class="col-form-label fw-bold fs-6">
                            {{ __('Short Description') }}
                        </x-metronic.label>
                        <x-metronic.textarea id="short_description"
                            name="short_description">{{ old('short_description', $blog->short_description) }}</x-metronic.textarea>
                    </div>

                    <!-- Author Image -->
                    <div class="col-lg-3 mb-3">
                        <x-metronic.label for="author_image" class="col-form-label fw-bold fs-6">
                            {{ __('Author Image') }}
                        </x-metronic.label>
                        <x-metronic.file-input id="author_image" name="author_image"></x-metronic.file-input>
                        @error('author_image')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                        <img src="{{ !empty($blog->author_image) ? url('storage/' . $blog->author_image) : 'No Image' }}"
                            height="70" width="70" alt="" class="mt-3">
                    </div>

                    <!-- Author Name -->
                    <div class="col-lg-3 mb-3">
                        <x-metronic.label for="author_name" class="col-form-label fw-bold fs-6">
                            {{ __('Author Name') }}
                        </x-metronic.label>
                        <x-metronic.input id="author_name" type="text" name="author_name"
                            :value="old('author_name', $blog->author_name)"></x-metronic.input>
                    </div>

                    <!-- Quote -->
                    <div class="col-lg-6 mb-3">
                        <x-metronic.label for="quote" class="col-form-label fw-bold fs-6">
                            {{ __('Quote') }}
                        </x-metronic.label>
                        <x-metronic.textarea id="quote"
                            name="quote">{{ old('quote', $blog->quote) }}</x-metronic.textarea>
                    </div>

                    <!-- Image One -->
                    <div class="col-lg-3 mb-3">
                        <x-metronic.label for="image_one" class="col-form-label fw-bold fs-6">
                            {{ __('Image One') }}
                        </x-metronic.label>
                        <x-metronic.file-input id="image_one" name="image_one"></x-metronic.file-input>
                        @error('image_one')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                        <img src="{{ !empty($blog->image_one) ? url('storage/' . $blog->image_one) : 'No Image' }}"
                            height="70" width="70" alt="" class="mt-3">
                    </div>

                    <!-- Description One -->
                    <div class="col-lg-9 mb-3">
                        <x-metronic.label for="long_description_one" class="col-form-label fw-bold fs-6">
                            {{ __('Description One') }}
                        </x-metronic.label>
                        <textarea id="long_description_one" class="ckeditor" name="long_description_one">{{ old('long_description_one', $blog->long_description_one) }}</textarea>
                    </div>

                    <!-- Image Two -->
                    <div class="col-lg-3 mb-3">
                        <x-metronic.label for="image_two" class="col-form-label fw-bold fs-6">
                            {{ __('Image Two') }}
                        </x-metronic.label>
                        <x-metronic.file-input id="image_two" name="image_two"></x-metronic.file-input>
                        @error('image_two')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                        <img src="{{ !empty($blog->image_two) ? url('storage/' . $blog->image_two) : 'No Image' }}"
                            height="70" width="70" alt="" class="mt-3">
                    </div>

                    <!-- Description Two -->
                    <div class="col-lg-9 mb-3">
                        <x-metronic.label for="long_description_two" class="col-form-label fw-bold fs-6">
                            {{ __('Description Two') }}
                        </x-metronic.label>
                        <textarea id="long_description_two" class="ckeditor" name="long_description_two">{{ old('long_description_two', $blog->long_description_two) }}</textarea>
                    </div>

                    <!-- Video -->
                    <div class="col-lg-3 mb-3">
                        <x-metronic.label for="video" class="col-form-label fw-bold fs-6">
                            {{ __('Video') }}
                        </x-metronic.label>
                        <x-metronic.file-input id="video" name="video"></x-metronic.file-input>
                    </div>

                    <!-- Video Description -->
                    <div class="col-lg-9 mb-3">
                        <x-metronic.label for="video_description" class="col-form-label fw-bold fs-6">
                            {{ __('Video Description') }}
                        </x-metronic.label>
                        <textarea id="video_description" class="ckeditor" name="video_description">{{ old('video_description', $blog->video_description) }}</textarea>
                    </div>

                    <!-- Meta Title -->
                    <div class="col-lg-3 mb-3">
                        <x-metronic.label for="meta_title" class="col-form-label fw-bold fs-6">
                            {{ __('Meta Title') }}
                        </x-metronic.label>
                        <x-metronic.input id="meta_title" type="text" name="meta_title"
                            placeholder="Enter SEO title" :value="old('meta_title', $blog->meta_title)"></x-metronic.input>
                    </div>

                    <!-- Meta Tags -->
                    <div class="col-lg-4 mb-3">
                        <x-metronic.label for="meta_tags" class="col-form-label fw-bold fs-6">
                            {{ __('Meta Tags') }}
                        </x-metronic.label>
                        <x-metronic.input class="tags" type="text" name="meta_tags"
                            placeholder="e.g: html,css,laravel" :value="old('meta_tags', $blog->meta_tags)"></x-metronic.input>
                    </div>

                    <!-- Meta Description -->
                    <div class="col-lg-5 mb-3">
                        <x-metronic.label for="meta_description" class="col-form-label fw-bold fs-6">
                            {{ __('Meta Description') }}
                        </x-metronic.label>
                        <x-metronic.textarea id="meta_description"
                            name="meta_description">{{ old('meta_description', $blog->meta_description) }}</x-metronic.textarea>
                    </div>

                    <!-- Tags -->
                    <div class="col-lg-10 mb-3">
                        <x-metronic.label for="tags" class="col-form-label fw-bold fs-6">
                            {{ __('Tags') }}
                        </x-metronic.label>
                        <x-metronic.input type="text" id="tags" name="tags"
                            placeholder="e.g. Laravel, PHP, Blog" :value="old('tags', $blog->tags)"></x-metronic.input>
                    </div>

                    <!-- Featured -->
                    <div class="col-lg-2 mb-3">
                        <x-metronic.label for="is_featured" class="col-form-label fw-bold fs-6">
                            {{ __('Feature this post?') }}
                        </x-metronic.label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_featured" value="1"
                                {{ old('is_featured', $blog->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                {{ __('Yes, mark as featured') }}
                            </label>
                        </div>
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
