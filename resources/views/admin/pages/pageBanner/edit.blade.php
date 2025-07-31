<x-admin-app-layout :title="'Page Banner Edit'">
    <div class="card card-flash">
        <div class="card-header mt-6">
            <div class="card-title"></div>
            <div class="card-toolbar">
                <a href="{{ route('admin.banner.index') }}" class="btn btn-light-info rounded-2">
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

            <form class="form" action="{{ route('admin.banner.update',$banner->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-lg-4 mb-7">
                        <x-metronic.label for="name"
                            class="col-form-label fw-bold fs-6 required">{{ __('Page Name') }}
                        </x-metronic.label>
                        <x-metronic.select-option id="page_name" name="page_name" data-hide-search="true"
                            data-placeholder="Select an option">
                            <option></option>
                            <option value="home_slider" @selected($banner->page_name == 'home_slider')>Home Page Slider</option>
                            <option value="home_slider_bottom_first" @selected($banner->page_name == 'home_slider_bottom_first')>Home Page Slider Bottom First</option>
                            <option value="home_slider_bottom_second" @selected($banner->page_name == 'home_slider_bottom_second')>Home Page Slider Bottom Second</option>
                            <option value="home_slider_bottom_third" @selected($banner->page_name == 'home_slider_bottom_third')>Home Page Slider Bottom Third</option>
                            <option value="user" @selected($banner->page_name == 'user')>User Panel</option>
                            <option value="cart" @selected($banner->page_name == 'cart')>Cart Page</option>
                            <option value="checkout" @selected($banner->page_name == 'checkout')>Checkout Page</option>
                            <option value="faq" @selected($banner->page_name == 'faq')>FAQ</option>
                            <option value="terms" @selected($banner->page_name == 'terms')>Terms & Condition</option>
                            <option value="privacy" @selected($banner->page_name == 'privacy')>Privacy Policy</option>
                        </x-metronic.select-option>
                    </div>
                    {{-- <div class="col-lg-4 mb-7">
                        <x-metronic.label for="image" class="col-form-label fw-bold fs-6 required">{{ __('Image') }}
                        </x-metronic.label>
                        <x-metronic.file-input id="image" type="file" name="image" :source="asset('storage/'.$banner->image)"></x-metronic.file-input>
                    </div> --}}
                    <div class="col-lg-4">
                        <x-metronic.label for="bg_image" class="col-form-label fw-bold fs-6 required">{{ __('Background Image') }}
                        </x-metronic.label>
                        <x-metronic.file-input id="bg_image" type="file" name="bg_image" :source="asset('storage/'.$banner->bg_image)"></x-metronic.file-input>
                    </div>
                    <div class="col-lg-4 mb-7">
                        <x-metronic.label for="title" class="col-form-label fw-bold fs-6 ">{{ __('Title') }}
                        </x-metronic.label>

                        <x-metronic.input id="title" type="text" name="title" placeholder="Enter the Title"
                            :value="old('title',$banner->title)"></x-metronic.input>
                    </div>
                    <div class="col-lg-4 mb-7">
                        <x-metronic.label for="subtitle" class="col-form-label fw-bold fs-6 ">{{ __('Subtitle') }}
                        </x-metronic.label>

                        <x-metronic.input id="subtitle" type="text" name="subtitle" placeholder="Enter the Subtitle"
                            :value="old('subtitle',$banner->subtitle)"></x-metronic.input>
                    </div>
                    <div class="col-lg-4 mb-7">
                        <x-metronic.label for="banner_link" class="col-form-label fw-bold fs-6 ">{{ __('Banner Link') }}
                        </x-metronic.label>

                        <x-metronic.input id="banner_link" type="text" name="banner_link" placeholder="Enter the Banner_Link"
                            :value="old('banner_link',$banner->banner_link)"></x-metronic.input>
                    </div>
                    <div class="col-lg-4 mb-7">
                        <x-metronic.label for="badge" class="col-form-label fw-bold fs-6 ">{{ __('Badge') }}
                        </x-metronic.label>

                        <x-metronic.input id="badge" type="text" name="badge" placeholder="Enter the Badge"
                            :value="old('badge',$banner->badge)"></x-metronic.input>
                    </div>
                    <div class="col-lg-4 mb-7">
                        <x-metronic.label for="button_name" class="col-form-label fw-bold fs-6 ">{{ __('Button Name') }}
                        </x-metronic.label>

                        <x-metronic.input id="button_name" type="text" name="button_name"
                            placeholder="Enter the Button Name" :value="old('button_name',$banner->button_name)"></x-metronic.input>
                    </div>
                    <div class="col-lg-4 mb-7">
                        <x-metronic.label for="button_link" class="col-form-label fw-bold fs-6 ">{{ __('Button Link') }}
                        </x-metronic.label>

                        <x-metronic.input id="button_link" type="url" name="button_link"
                            placeholder="Enter the Button Link" :value="old('button_link',$banner->button_link)"></x-metronic.input>
                    </div>
                    <div class="col-lg-4 mb-7">
                        <x-metronic.label for="status" class="col-form-label required fw-bold fs-6">
                            {{ __('Select a Status ') }}</x-metronic.label>
                        <x-metronic.select-option id="status" name="status" data-hide-search="true"
                            data-placeholder="Select an option">
                            <option></option>
                            <option value="active" @selected($banner->status == "active") >Active</option>
                            <option value="inactive" @selected($banner->status == "inactive") >Inactive</option>
                        </x-metronic.select-option>
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
</x-admin-app-layout>
