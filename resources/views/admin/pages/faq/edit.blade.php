<x-admin-app-layout :title="'Faq Edi'">
    <div class="card card-flash">
        <!--begin::Card header-->
        <div class="card-header mt-6">
            <div class="card-title"></div>

            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Button-->
                <a href="{{ route('admin.faq.index') }}" class="btn btn-light-info">
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

            <form method="POST" action="{{ route('admin.faq.update', $faq->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">

                    <div class="col-lg-3 mb-3">
                        <x-metronic.label for="status" class="col-form-label required fw-bold fs-6">
                            {{ __('Select a Status ') }}
                        </x-metronic.label>
                        <x-metronic.select-option id="status" name="status" data-hide-search="true"
                            data-placeholder="Select an option">
                            <option value="active" {{ $faq->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $faq->status == 'inactive' ? 'selected' : '' }}>Inactive
                            </option>
                        </x-metronic.select-option>
                    </div>

                    <div class="col-lg-2 mb-3">
                        <x-metronic.label for="order" class="col-form-label required fw-bold fs-6">
                            {{ __('Order') }}
                        </x-metronic.label>
                        <x-metronic.input id="order" type="number" name="order" placeholder="eg: number(1)"
                            value="{{ old('order', $faq->order) }}"></x-metronic.input>
                    </div>

                    <div class="col-lg-12 mb-3">
                        <x-metronic.label for="question" class="col-form-label required fw-bold fs-6">
                            {{ __('Question') }}
                        </x-metronic.label>
                        <x-metronic.input id="question" type="text" name="question" placeholder="Enter the question"
                            value="{{ old('question', $faq->question) }}"></x-metronic.input>
                    </div>

                    <div class="col-lg-12 mb-3">
                        <x-metronic.label for="answer" class="col-form-label fw-bold fs-6">
                            {{ __('Answer') }}
                        </x-metronic.label>
                        <x-metronic.textarea id="answer"
                            name="answer">{{ old('answer', $faq->answer) }}</x-metronic.textarea>
                    </div>

                    <div class="col-lg-12 mb-3">
                        <x-metronic.label for="additional_info" class="col-form-label fw-bold fs-6">
                            {{ __('Additional Info') }}
                        </x-metronic.label>
                        <textarea id="additional_info" class="ckeditor" name="additional_info">{{ old('additional_info', $faq->additional_info) }}</textarea>
                    </div>

                </div>

                <div class="text-end pt-15">
                    <x-metronic.button type="submit" class="dark rounded-1 px-5">
                        {{ __('Update') }}
                    </x-metronic.button>
                </div>

            </form>

        </div>
    </div>

</x-admin-app-layout>
