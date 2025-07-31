<x-admin-app-layout :title="'Blog Add'">
    <div class="card card-flash">

        <div class="card-header mt-6">
            <div class="card-title"></div>


            <div class="card-toolbar">
                <a href="{{ route('admin.blogs.index') }}" class="btn btn-light-info rounded-2">
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
            <form class="form" action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-lg-4">
                        <x-metronic.label for="admin_id"
                            class="col-form-label fw-bold fs-6">{{ __('Select a author name') }}</x-metronic.label>
                        <x-metronic.select-option id="admin_id" name="admin_id" data-hide-search="false"
                            data-placeholder="Select an option">
                            <option></option>
                            @foreach ($admins as $admin)
                                <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                            @endforeach
                        </x-metronic.select-option>
                    </div>

                    <div class="col-lg-4">
                        <x-metronic.label for="category_id"
                            class="col-form-label fw-bold fs-6">{{ __('Select a category name') }}</x-metronic.label>
                        <x-metronic.select-option id="category_id" name="category_id" data-hide-search="false"
                            data-placeholder="Select an option">
                            <option></option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </x-metronic.select-option>
                    </div>

                    <div class="col-lg-4">
                        <x-metronic.label for="tag_id"
                            class="col-form-label fw-bold fs-6">{{ __('Select a tag name') }}</x-metronic.label>
                        <x-metronic.select-option id="tag_id" name="tag_id" data-hide-search="false"
                            data-placeholder="Select an option">
                            <option></option>
                            @foreach ($tags as $tag)
                                <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                            @endforeach
                        </x-metronic.select-option>
                    </div>

                    <div class="col-lg-4 mb-7">
                        <x-metronic.label for="title"
                            class="col-form-label fw-bold fs-6 required">{{ __('Title') }}
                        </x-metronic.label>

                        <x-metronic.input id="title" type="text" name="title" :value="old('title')"
                            placeholder="Enter the Title" required></x-metronic.input>
                    </div>

                    <div class="col-lg-4 mb-7">
                        <x-metronic.label for="url"
                            class="col-form-label fw-bold fs-6 required">{{ __('Url') }}
                        </x-metronic.label>

                        <x-metronic.input id="url" type="url" name="url" :value="old('url')"
                            placeholder="Enter the Url"></x-metronic.input>
                    </div>

                    <div class="col-lg-4 mb-7">
                        <x-metronic.label for="badge"
                            class="col-form-label fw-bold fs-6 required">{{ __('Badge') }}
                        </x-metronic.label>

                        <x-metronic.input id="badge" type="text" name="badge" :value="old('badge')"
                            placeholder="Enter the Badge"></x-metronic.input>
                    </div>

                    <div class="col-lg-4 mb-7">
                        <x-metronic.label for="summary"
                            class="col-form-label fw-bold fs-6 required">{{ __('Summary') }}
                        </x-metronic.label>

                        <x-metronic.input id="summary" type="text" name="summary" :value="old('summary')"
                            placeholder="Enter the Summary"></x-metronic.input>
                    </div>

                    <div class="col-lg-4 mb-7">
                        <x-metronic.label for="published_at"
                            class="col-form-label fw-bold fs-6 required">{{ __('Published At') }}
                        </x-metronic.label>

                        <x-metronic.input id="published_at" type="text" name="published_at" :value="old('published_at')"
                            placeholder="Enter the Published At"></x-metronic.input>
                    </div>

                    <div class="col-lg-4 mb-7">
                        <x-metronic.label for="status" class="col-form-label required fw-bold fs-6">
                            {{ __('Select a Status ') }}</x-metronic.label>
                        <x-metronic.select-option id="status" name="status" data-hide-search="true"
                            data-placeholder="Select an option">
                            <option></option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </x-metronic.select-option>
                    </div>

                    <div class="col-lg-4 mb-7">
                        <x-metronic.label for="logo" class="col-form-label fw-bold fs-6 ">{{ __('Logo') }}
                        </x-metronic.label>

                        <x-metronic.input id="logo" type="file" name="logo"></x-metronic.input>
                    </div>

                    <div class="col-lg-4 mb-7">
                        <x-metronic.label for="default-editor"
                            class="col-form-label fw-bold fs-6 ">{{ __('Description') }}
                        </x-metronic.label>

                        <x-metronic.textarea id="default-editor" name="description"></x-metronic.textarea>
                    </div>
                </div>
                <div class="text-center pt-15">
                    <x-metronic.button type="submit" class="primary">
                        {{ __('Submit') }}
                    </x-metronic.button>
                </div>

            </form>

        </div>
    </div>
    @push('scripts')
        <script>
            tinymce.init({
                selector: 'textarea#default-editor',
                // height: "480",
                // toolbar: "advlist | autolink | link image | lists charmap | print preview",
                // plugins: "advlist autolink link image lists charmap print preview"
                plugins: 'a11ychecker advcode table advlist lists image media anchor link autoresize autosave code codesample directionality emoticons fullscreen hr imagetools insertdatetime legacyoutput nonbreaking noneditable pagebreak paste preview print save searchreplace tabfocus template textpattern toc visualblocks visualchars wordcount autosave charmap emoticons hr insertdatetime legacyoutput nonbreaking pagebreak preview print save searchreplace template toc visualblocks visualchars wordcount a11ychecker a11ycheckerchecker a11ychecklist advcode codesample directionality imagetools media textpattern noneditable tabfocus visualchars tabfocus',
                toolbar: 'a11ycheck | blocks bold forecolor backcolor | bullist numlist | link image media anchor | table | code | formatselect | alignleft aligncenter alignright alignjustify | fontselect fontsizeselect | outdent indent | removeformat | help | fullscreen | preview print save | insertfile pagebreak | charmap emoticons | ltr rtl | visualchars visualblocks nonbreaking template | searchreplace | toc | insertdatetime | legacyoutput | autosave',
                // a11y_advanced_options: true,
                // a11ychecker_html_version: 'html5',
                // a11ychecker_level: 'aaa',
                // content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }'
            });
        </script>
    @endpush
</x-admin-app-layout>
