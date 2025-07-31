<div class="row text-center py-5">
    <h5 class="text-center m-0 p-0">Footer Section</h5>
</div>
<div class="row">
    <div class="col-lg-6 mb-7">
        <x-metronic.label for="copyright_title" class="col-form-label fw-bold fs-6 ">{{ __('Copyright Title') }}
        </x-metronic.label>

        <x-metronic.input id="copyright_title" type="text" name="copyright_title" :value="old('copyright_title', optional($setting)->copyright_title)"
            placeholder="Copyright Title"></x-metronic.input>
    </div>
    <div class="col-lg-6 mb-7">
        <x-metronic.label for="copyright_url" class="col-form-label fw-bold fs-6 ">{{ __('Copyright URL') }}
        </x-metronic.label>

        <x-metronic.input id="copyright_url" type="text" name="copyright_url" :value="old('copyright_url', optional($setting)->copyright_url)"
            placeholder="Copyright URL"></x-metronic.input>
    </div>
</div>
