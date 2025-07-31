<x-admin-app-layout :title="'Return and Policy Edit'">
    <div class="card card-flash">
        <!--begin::Card header-->
        <div class="card-header mt-6">
            <div class="card-title"></div>

            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Button-->
                <a href="{{ route('admin.return-policy.index') }}" class="btn btn-light-info">
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

            <form method="POST" action="{{ route('admin.return-policy.update', $item->id) }}"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">

                    <div class="col-lg-3 mb-3">
                        <x-metronic.label for="status" class="col-form-label required fw-bold fs-6">
                            {{ __('Select a Status ') }}</x-metronic.label>
                        <x-metronic.select-option id="status" name="status" data-hide-search="true"
                            data-placeholder="Select an option">
                            <option></option>
                            <option value="active" {{ old('status', $item->status) == 'active' ? 'selected' : '' }}>
                                Active</option>
                            <option value="inactive" {{ old('status', $item->status) == 'inactive' ? 'selected' : '' }}>
                                Inactive</option>
                        </x-metronic.select-option>
                    </div>

                    <div class="col-lg-3 mb-3">
                        <x-metronic.label for="title" class="col-form-label required fw-bold fs-6">
                            {{ __('Title') }}</x-metronic.label>
                        <x-metronic.input id="title" type="text" name="title" placeholder="Enter the title"
                            :value="old('title', $item->title)"></x-metronic.input>
                    </div>

                    <div class="col-lg-3 mb-3">
                        <x-metronic.label for="version" class="col-form-label required fw-bold fs-6">
                            {{ __('Version') }}</x-metronic.label>
                        <x-metronic.input id="version" type="text" name="version" placeholder="Enter the version"
                            :value="old('version', $item->version)"></x-metronic.input>
                    </div>

                    <div class="col-lg-3 mb-3">
                        <x-metronic.label for="effective_date" class="col-form-label fw-bold fs-6">
                            {{ __('Effective Date') }}</x-metronic.label>
                        <x-metronic.input id="effective_date" type="date" name="effective_date"
                            :value="old('effective_date', $item->effective_date)"></x-metronic.input>
                    </div>

                    <div class="col-lg-3 mb-3">
                        <x-metronic.label for="expiration_date" class="col-form-label fw-bold fs-6">
                            {{ __('Expiration Date') }}</x-metronic.label>
                        <x-metronic.input id="expiration_date" type="date" name="expiration_date"
                            :value="old('expiration_date', $item->expiration_date)"></x-metronic.input>
                    </div>

                    <div class="col-lg-12 mb-3">
                        <x-metronic.label for="content"
                            class="col-form-label fw-bold fs-6 ">{{ __('Content') }}</x-metronic.label>
                        <textarea id="content" class="ckeditor" name="content">{{ old('content', $item->content) }}</textarea>
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
