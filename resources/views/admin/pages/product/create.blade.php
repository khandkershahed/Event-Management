<x-admin-app-layout :title="'Product Add'">
    <div class="card card-flash">
        <!--begin::Card header-->
        <div class="card-header mt-6">
            <div class="card-title"></div>

            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Button-->
                <a href="{{ route('admin.product.index') }}" class="btn btn-light-info">
                    <!--begin::Svg Icon | path: product/duotune/general/gen035.svg-->
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

            <form method="POST" action="{{ route('admin.product.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">

                    <div class="col-lg-2 mb-3">
                        <x-metronic.label for="status" class="col-form-label required fw-bold fs-6">
                            {{ __('Select a Status ') }}</x-metronic.label>
                        <x-metronic.select-option id="status" name="status" data-hide-search="true"
                            data-placeholder="Select an option">
                            <option></option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </x-metronic.select-option>
                    </div>

                    <!-- brand_id -->
                    <div class="col-lg-2 mb-7">
                        <x-metronic.label for="brand_id"
                            class="col-form-label fw-bold fs-6">{{ __('Select a Brand') }}</x-metronic.label>
                        <x-metronic.select-option id="brand_id" name="brand_id" data-hide-search="false"
                            data-placeholder="Select an option">
                            <option></option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </x-metronic.select-option>
                    </div>

                    <!-- category_id -->
                    <div class="col-lg-2 mb-7">
                        <x-metronic.label for="category_id"
                            class="col-form-label fw-bold fs-6">{{ __('Select a Category') }}</x-metronic.label>

                        <x-metronic.select-option id="category_id" name="category_id" data-hide-search="false"
                            data-placeholder="Select an option" required>
                            <option></option>
                            @foreach ($allCategories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </x-metronic.select-option>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <x-metronic.label for="name"
                            class="col-form-label required fw-bold fs-6">{{ __('Name') }}</x-metronic.label>
                        <x-metronic.input id="name" type="text" name="name" placeholder="Enter the name"
                            :value="old('name')"></x-metronic.input>
                    </div>

                    <div class="col-lg-2 mb-3">
                        <x-metronic.label for="sku"
                            class="col-form-label fw-bold fs-6">{{ __('SKU') }}</x-metronic.label>
                        <x-metronic.input id="sku" type="text" name="sku" placeholder="Enter the sku"
                            :value="old('sku')"></x-metronic.input>
                    </div>

                    <div class="col-lg-3 mb-3">
                        <x-metronic.label for="mf_code"
                            class="col-form-label fw-bold fs-6">{{ __('Mf Code') }}</x-metronic.label>
                        <x-metronic.input id="mf_code" type="text" name="mf_code" placeholder="Enter the mf code"
                            :value="old('mf_code')"></x-metronic.input>
                    </div>

                    <div class="col-lg-3 mb-3">
                        <x-metronic.label for="qty"
                            class="col-form-label fw-bold fs-6">{{ __('Qty') }}</x-metronic.label>
                        <x-metronic.input id="qty" type="number" name="qty" placeholder="Enter the Qty"
                            :value="old('qty')"></x-metronic.input>
                    </div>

                    <div class="col-lg-3 mb-3">
                        <x-metronic.label for="currency"
                            class="col-form-label fw-bold fs-6">{{ __('Currency') }}</x-metronic.label>
                        <x-metronic.select-option id="status" name="currency" data-hide-search="true"
                            data-placeholder="Select an option">
                            <option></option>
                            <option value="$">Dollar</option>
                            <option value="BDT">Taka</option>
                        </x-metronic.select-option>
                    </div>

                    <div class="col-lg-3 mb-3">
                        <x-metronic.label for="price"
                            class="col-form-label fw-bold fs-6">{{ __('Price') }}</x-metronic.label>
                        <x-metronic.input id="price" type="text" name="price" placeholder="Enter the price"
                            :value="old('price')"></x-metronic.input>
                    </div>

                    <div class="col-lg-3 mb-3">
                        <x-metronic.label for="discount_price"
                            class="col-form-label fw-bold fs-6">{{ __('Discount Price') }}</x-metronic.label>
                        <x-metronic.input id="discount_price" type="text" name="discount_price"
                            placeholder="Enter the discount price" :value="old('discount_price')"></x-metronic.input>
                    </div>

                    <div class="col-lg-3 mb-3">
                        <x-metronic.label for="supplier"
                            class="col-form-label fw-bold fs-6">{{ __('Supplier') }}</x-metronic.label>
                        <x-metronic.input id="supplier" type="text" name="supplier"
                            placeholder="Enter the Supplier" :value="old('supplier')"></x-metronic.input>
                    </div>

                    <div class="col-lg-3 mb-3">
                        <x-metronic.label for="warehouse_location"
                            class="col-form-label fw-bold fs-6">{{ __('Warehouse Location') }}</x-metronic.label>
                        <x-metronic.input id="warehouse_location" type="text" name="warehouse_location"
                            placeholder="Warehouse Location" :value="old('warehouse_location')"></x-metronic.input>
                    </div>

                    <div class="col-lg-3 mb-3">
                        <x-metronic.label for="weight"
                            class="col-form-label fw-bold fs-6">{{ __('Weight') }}</x-metronic.label>
                        <x-metronic.input id="weight" type="text" name="weight" placeholder="Weight"
                            :value="old('weight')"></x-metronic.input>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <x-metronic.label for="tags"
                            class="col-form-label fw-bold fs-6">{{ __('Tag') }}</x-metronic.label>
                        <x-metronic.input id="tags" type="text" name="tags" placeholder="Tags"
                            :value="old('tags')"></x-metronic.input>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <x-metronic.label for="meta_title"
                            class="col-form-label fw-bold fs-6">{{ __('Meta Title') }}</x-metronic.label>
                        <x-metronic.input id="meta_title" type="text" name="meta_title" placeholder="Meta Title"
                            :value="old('meta_title')"></x-metronic.input>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <x-metronic.label for="meta_content"
                            class="col-form-label fw-bold fs-6">{{ __('Meta Content') }}</x-metronic.label>
                        <x-metronic.input id="meta_content" type="text" name="meta_content"
                            placeholder="Meta Content" :value="old('meta_content')"></x-metronic.input>
                    </div>

                    <div class="col-lg-12 mb-3">
                        <x-metronic.label for="meta_description"
                            class="col-form-label fw-bold fs-6 ">{{ __('Meta Description') }}
                        </x-metronic.label>

                        <x-metronic.textarea id="meta_description" name="meta_description"></x-metronic.textarea>
                    </div>

                    <div class="col-lg-12 mb-3">
                        <x-metronic.label for="short_description"
                            class="col-form-label fw-bold fs-6 ">{{ __('Short Description') }}
                        </x-metronic.label>

                        <x-metronic.textarea id="short_description" name="short_description"></x-metronic.textarea>
                    </div>


                    <div class="col-lg-12 mb-3">
                        <x-metronic.label for="specification"
                            class="col-form-label fw-bold fs-6 ">{{ __('Specification') }}
                        </x-metronic.label>

                        {{-- <x-metronic.textarea id="specification" name="specification" class="ckeditor"></x-metronic.textarea> --}}
                        <textarea id="about" class="ckeditor" name="specification">{{ old('specification') }}</textarea>
                    </div>

                    <div class="col-lg-12 mb-3">
                        <x-metronic.label for="description"
                            class="col-form-label fw-bold fs-6 ">{{ __('Description') }}
                        </x-metronic.label>

                        {{-- <x-metronic.textarea id="description" name="description" class="ckeditor"></x-metronic.textarea> --}}
                        <textarea id="about" class="ckeditor" name="long_description">{{ old('long_description') }}</textarea>
                    </div>



                    <div class="col-lg-1 mb-3">
                        <x-metronic.label for="is_featured" class="col-form-label fw-bold fs-6">
                            {{ __('Featured') }}
                        </x-metronic.label>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured"
                                value="1" {{ old('is_featured') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                {{ __('Yes') }}
                            </label>
                        </div>
                    </div>

                    <div class="col-lg-1 mb-3">
                        <x-metronic.label for="is_selling" class="col-form-label fw-bold fs-6">
                            {{ __('Selling') }}
                        </x-metronic.label>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_selling" name="is_selling"
                                value="1" {{ old('is_selling') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_selling">
                                {{ __('Yes') }}
                            </label>
                        </div>
                    </div>

                    <div class="col-lg-1 mb-3">
                        <x-metronic.label for="is_new" class="col-form-label fw-bold fs-6">
                            {{ __('New') }}
                        </x-metronic.label>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_new" name="is_new"
                                value="1" {{ old('is_new') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_new">
                                {{ __('Yes') }}
                            </label>
                        </div>
                    </div>

                    <div class="col-lg-1 mb-3">
                        <x-metronic.label for="hot_deal" class="col-form-label fw-bold fs-6">
                            {{ __('Hot Deal') }}
                        </x-metronic.label>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="hot_deal" name="hot_deal"
                                value="1" {{ old('hot_deal') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_new">
                                {{ __('Yes') }}
                            </label>
                        </div>
                    </div>


                    <div class="col-lg-4 mb-3">
                        <x-metronic.label for="thumbnail_image"
                            class="col-form-label fw-bold fs-6">{{ __('Thumbnail Image') }}
                        </x-metronic.label>

                        <x-metronic.file-input id="thumbnail_image" name="thumbnail_image"
                            :value="old('thumbnail_image')"></x-metronic.file-input>
                    </div>

                </div>

                <div class="text-end pt-15">

                    <x-metronic.button type="submit"
                        class="dark rounded-1 px-5">{{ __('Submit') }}</x-metronic.button>

                </div>

            </form>

        </div>
    </div>

</x-admin-app-layout>
