<div class="row text-center py-5">
    <h5 class="text-center m-0 p-0">Website URL and Social Links</h5>
</div>
<div class="row mt-10">
    <div class="fv-row col-lg-4 mb-7">
        <x-metronic.label class="fw-semibold fs-6 mb-2 required"><i class="fs-3 fa-solid fa-globe pe-2"></i>WebSite
            URL</x-metronic.label>
        <x-metronic.input type="url" name="website_url" value="{{ optional($setting)->website_url }}"
            class="form-control form-control-solid mb-3 mb-lg-0" placeholder="WebSite URL" />
    </div>
    <div class="fv-row col-lg-4 mb-7">
        <x-metronic.label class="fw-semibold fs-6 mb-2 required"><i class="fs-3 fa-brands fa-facebook pe-2"></i>Facebook
            URL</x-metronic.label>
        <x-metronic.input type="url" name="facebook_url" value="{{ optional($setting)->facebook_url }}"
            class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Facebook URL" />
    </div>
    <div class="fv-row col-lg-4 mb-7">

        <x-metronic.label class="fw-semibold fs-6 mb-2"><i class="fs-3 fa-brands fa-twitter pe-2"></i>Twitter
            URL</x-metronic.label>
        <x-metronic.input type="url" name="twitter_url" value="{{ optional($setting)->twitter_url }}"
            class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Twitter URL" />
    </div>
    <div class="fv-row col-lg-4 mb-7">

        <x-metronic.label class="fw-semibold fs-6 mb-2"><i class="fs-3 fa-brands fa-instagram pe-2"></i>Instagram
            URL</x-metronic.label>
        <x-metronic.input type="url" name="instagram_url" value="{{ optional($setting)->instagram_url }}"
            class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Instagram URL" />
    </div>
    <div class="fv-row col-lg-4 mb-7">

        <x-metronic.label class="fw-semibold fs-6 mb-2"><i class="fs-3 fa-brands fa-youtube pe-2"></i>Youtube
            URL</x-metronic.label>
        <x-metronic.input type="url" name="youtube_url" value="{{ optional($setting)->youtube_url }}"
            class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Youtube URL" />
    </div>
    <div class="fv-row col-lg-4 mb-7">
        <x-metronic.label class="fw-semibold fs-6 mb-2 required"><i
                class="fs-3 fa-brands fa-linkedin pe-2 "></i>{{ __('Linkedin URL') }}</x-metronic.label>
        <x-metronic.input type="url" name="linkedin_url" value="{{ optional($setting)->linkedin_url }}"
            class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Linkedin URL" />
    </div>
    <div class="fv-row col-lg-4 mb-7">
        <x-metronic.label class="fw-semibold fs-6 mb-2"><i
                class="fs-3 fa-brands fa-whatsapp pe-2"></i>{{ __('Whats App URL') }}</x-metronic.label>
        <x-metronic.input type="url" name="whatsapp_url" value="{{ optional($setting)->whatsapp_url }}"
            class="form-control form-control-solid mb-3 mb-lg-0" placeholder="What's App URL" />
    </div>
    <div class="fv-row col-lg-4 mb-7">
        <x-metronic.label class="fw-semibold fs-6 mb-2"><i
                class="fs-3 fa-brands fa-pinterest pe-2"></i>{{ __('Pinterest URL') }}</x-metronic.label>
        <x-metronic.input type="url" name="pinterest_url" value="{{ optional($setting)->pinterest_url }}"
            class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Pinterest URL" />
    </div>
    <div class="fv-row col-lg-4 mb-7">
        <x-metronic.label class="fw-semibold fs-6 mb-2"><i
                class="fs-3 fa-brands fa-tiktok pe-2"></i>{{ __('Tiktok URL') }}</x-metronic.label>
        <x-metronic.input type="url" name="youtube_url" value="{{ optional($setting)->youtube_url }}"
            class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Youtube URL" />
    </div>
</div>
