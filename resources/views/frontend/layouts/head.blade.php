<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="format-detection" content="telephone=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<link rel="shortcut icon"
    href="{{ !empty($setting->site_favicon) && file_exists(public_path('storage/' . $setting->site_favicon)) ? asset('storage/' . $setting->site_favicon) : asset('images/favicon.jpg') }}"
    type="image/x-icon" />
<meta name="title" content="{{ optional($setting)->website_name ?: 'Events Tailor' }}" />
<meta name="description" content="{{ optional($setting)->meta_description ?: 'Events Tailor' }}" />
<meta property="og:type" content="website" />
<meta property="og:url" content="{{ optional($setting)->site_url ?: config('app.url') }}" />
<meta property="og:title" content="{{ optional($setting)->website_name ?: 'Events Tailor' }}" />
<meta property="og:description" content="{{ optional($setting)->meta_description ?: 'Events Tailor' }}" />
@php
    $slider = \App\Models\PageBanner::active()
        ->where('page_name', 'home_slider')
        ->latest('id')
        ->first(['bg_image']);
@endphp
<meta property="og:image"
    content="{{ !empty($slider->bg_image) ? asset('storage/' . $slider->bg_image) : asset('images/image_why_choose.png') }}" />

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image" />
<meta property="twitter:url" content="{{ optional($setting)->site_url ?: config('app.url') }}" />
<meta property="twitter:title" content="{{ optional($setting)->website_name ?: 'Events Tailor' }}" />
<meta property="twitter:description" content="{{ optional($setting)->meta_description ?: 'Events Tailor' }}" />
<meta property="twitter:image"
    content="{{ !empty($slider->bg_image) ? asset('storage/' . $slider->bg_image) : asset('images/image_why_choose.png') }}" />

<link href="{{ asset('storage/' . optional($setting)->site_favicon) }}" rel="apple-touch-icon-precomposed">
<link href="{{ asset('storage/' . optional($setting)->site_favicon) }}" rel="shortcut icon" type="image/png">

<title>
    {{ optional($setting)->website_name ? optional($setting)->website_name : 'Events Tailor' }}
</title>

<!-- Stylesheets -->
<link rel="preconnect" href="https://fonts.googleapis.com/" />
<link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
<link
    href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap"
    rel="stylesheet" />
<link href="{{ asset('frontend/vendor/unicons-2.0.1/css/unicons.css') }}" rel="stylesheet" />
<!-- Vendor Stylesheets -->
<link href="{{ asset('frontend/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" />
<link href="{{ asset('frontend/vendor/OwlCarousel/assets/owl.carousel.css') }}" rel="stylesheet" />
<link href="{{ asset('frontend/vendor/OwlCarousel/assets/owl.theme.default.min.css') }}" rel="stylesheet" />
<link href="{{ asset('frontend/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />
<link href="{{ asset('frontend/vendor/bootstrap-select/dist/css/bootstrap-select.min.css') }}" rel="stylesheet" />
<link href="{{ asset('frontend/css/style.css?v=' . time()) }}" rel="stylesheet" />
<link href="{{ asset('frontend/css/responsive.css?v=' . time()) }}" rel="stylesheet" />
<link href="{{ asset('frontend/css/night-mode.css?v=' . time()) }}" rel="stylesheet" />
