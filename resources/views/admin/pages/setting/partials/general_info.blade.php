<div class="row text-center py-5">
    <h3 class="text-center m-0 p-0">Website Information</h3>
</div>

<div class="row">

    <div class="col-lg-3 mb-7">
        <x-metronic.label for="website_name" class="col-form-label fw-bold fs-6 ">{{ __('Website Name') }}
        </x-metronic.label>

        <x-metronic.input id="website_name" type="text" name="website_name" :value="old('website_name', optional($setting)->website_name)"
            placeholder="Site Name"></x-metronic.input>
    </div>

    <div class="col-lg-3 mb-7">
        <x-metronic.label for="contact_email" class="col-form-label fw-bold fs-6 ">{{ __('Contact Email') }}
        </x-metronic.label>

        <x-metronic.input id="contact_email" type="email" name="contact_email" :value="old('contact_email', optional($setting)->contact_email)"
            placeholder="Contact email address"></x-metronic.input>
    </div>

    <div class="col-lg-3 mb-7">
        <x-metronic.label for="support_email" class="col-form-label fw-bold fs-6 ">{{ __('Support Email') }}
        </x-metronic.label>

        <x-metronic.input id="support_email" type="email" name="support_email" :value="old('support_email', optional($setting)->support_email)"
            placeholder="Support Email address"></x-metronic.input>
    </div>

    <div class="col-lg-3 mb-7">
        <x-metronic.label for="info_email" class="col-form-label fw-bold fs-6 ">{{ __('Info Email') }}
        </x-metronic.label>

        <x-metronic.input id="info_email" type="email" name="info_email" :value="old('info_email', optional($setting)->info_email)"
            placeholder="Info Email address"></x-metronic.input>
    </div>

    <div class="col-lg-3 mb-7">
        <x-metronic.label for="sales_email" class="col-form-label fw-bold fs-6 ">{{ __('Sales Email') }}
        </x-metronic.label>

        <x-metronic.input id="sales_email" type="email" name="sales_email" :value="old('sales_email', optional($setting)->sales_email)"
            placeholder="Sales Email address"></x-metronic.input>
    </div>

    <div class="col-lg-3 mb-7">
        <x-metronic.label for="primary_phone" class="col-form-label fw-bold fs-6 ">{{ __('Primary Phone') }}
        </x-metronic.label>

        <x-metronic.input id="primary_phone" type="text" name="primary_phone" :value="old('primary_phone', optional($setting)->primary_phone)"
            placeholder="Primary phone"></x-metronic.input>
    </div>

    <div class="col-lg-3 mb-7">
        <x-metronic.label for="alternative_phone" class="col-form-label fw-bold fs-6 ">{{ __('Alternative Phone') }}
        </x-metronic.label>

        <x-metronic.input id="alternative_phone" type="text" name="alternative_phone" :value="old('alternative_phone', optional($setting)->alternative_phone)"
            placeholder="Alternative phone"></x-metronic.input>
    </div>

    <div class="col-lg-6 mb-7">
        <x-metronic.label for="address_line_one" class="col-form-label fw-bold fs-6">{{ __('Address Line One') }}
        </x-metronic.label>

        <textarea name="address_line_one" id="address_line_one" cols="1" rows="1" class="form-control"
            placeholder="Enter Address Line One">{!! $setting ? $setting->address_line_one : '' !!}</textarea>
    </div>

    <div class="col-lg-6 mb-7">
        <x-metronic.label for="address_line_two" class="col-form-label fw-bold fs-6">{{ __('Address Line Two') }}
        </x-metronic.label>

        <textarea name="address_line_two" id="address_line_two" cols="1" rows="1" class="form-control"
            placeholder="Enter Address Line Two'">{!! $setting ? $setting->address_line_two : '' !!}</textarea>
    </div>

    <div class="col-lg-4 mb-7">
        <x-metronic.label for="site_logo_white"
            class="col-form-label fw-bold fs-6 ">{{ __('Site Logo White (For Colorful Background)') }}
        </x-metronic.label>

        <x-metronic.file-input id="site_logo_white" name="site_logo_white" :source="asset('storage/' . optional($setting)->site_logo_white)"></x-metronic.file-input>
    </div>

    <div class="col-lg-4 mb-7">
        <x-metronic.label for="site_logo_black"
            class="col-form-label fw-bold fs-6 ">{{ __('Site Logo Colorful (For White Background)') }}
        </x-metronic.label>

        <x-metronic.file-input id="site_logo_black" name="site_logo_black" :source="asset('storage/' . optional($setting)->site_logo_black)"></x-metronic.file-input>
    </div>
    <div class="col-lg-4 mb-7">
        <x-metronic.label for="site_favicon" class="col-form-label fw-bold fs-6 ">{{ __('Site Favicon') }}
        </x-metronic.label>

        <x-metronic.file-input id="site_favicon" type="file" name="site_favicon"
            :source="asset('storage/' . optional($setting)->site_favicon)"></x-metronic.file-input>
    </div>
</div>
