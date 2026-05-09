@php
    $defaultTitle = optional($setting)->website_name ?: config('app.name', 'Events Tailor');
    $defaultDescription = optional($setting)->meta_description ?: 'Discover and book marketplace events online.';
    $defaultUrl = url()->current();
    $defaultImage = asset('images/image_why_choose.png');
    $meta = $seoMeta ?? [];
    $seoTitle = $meta['title'] ?? $defaultTitle;
    $seoDescription = $meta['description'] ?? $defaultDescription;
    $seoCanonical = $meta['canonical'] ?? $defaultUrl;
    $seoImage = $meta['image'] ?? $defaultImage;
    $seoType = $meta['og_type'] ?? 'website';
    $seoRobots = $meta['robots'] ?? 'index,follow';
    $twitterCard = $meta['twitter_card'] ?? 'summary_large_image';
    $jsonLdBlocks = $meta['json_ld'] ?? ($seoJsonLd ?? []);
@endphp
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="format-detection" content="telephone=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="robots" content="{{ $seoRobots }}">
<link rel="canonical" href="{{ $seoCanonical }}">
<link rel="shortcut icon" href="{{ !empty(optional($setting)->site_favicon) && file_exists(public_path('storage/' . optional($setting)->site_favicon)) ? asset('storage/' . optional($setting)->site_favicon) : asset('images/favicon.jpg') }}" type="image/x-icon" />
<meta name="title" content="{{ $seoTitle }}" />
<meta name="description" content="{{ $seoDescription }}" />
<meta property="og:type" content="{{ $seoType }}" />
<meta property="og:url" content="{{ $seoCanonical }}" />
<meta property="og:title" content="{{ $seoTitle }}" />
<meta property="og:description" content="{{ $seoDescription }}" />
<meta property="og:image" content="{{ $seoImage }}" />
<meta property="twitter:card" content="{{ $twitterCard }}" />
<meta property="twitter:url" content="{{ $seoCanonical }}" />
<meta property="twitter:title" content="{{ $seoTitle }}" />
<meta property="twitter:description" content="{{ $seoDescription }}" />
<meta property="twitter:image" content="{{ $seoImage }}" />
<link href="{{ !empty(optional($setting)->site_favicon) ? asset('storage/' . optional($setting)->site_favicon) : asset('images/favicon.jpg') }}" rel="apple-touch-icon-precomposed">
<link href="{{ !empty(optional($setting)->site_favicon) ? asset('storage/' . optional($setting)->site_favicon) : asset('images/favicon.jpg') }}" rel="shortcut icon" type="image/png">
<title>{{ $seoTitle }}</title>
@foreach($jsonLdBlocks as $jsonLdBlock)
    @if(!empty($jsonLdBlock))
        <script type="application/ld+json">{!! json_encode($jsonLdBlock, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}</script>
    @endif
@endforeach
<link rel="preconnect" href="https://fonts.googleapis.com/" />
<link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap" rel="stylesheet" />
<link href="{{ asset('frontend/vendor/unicons-2.0.1/css/unicons.css') }}" rel="stylesheet" />
<link href="{{ asset('frontend/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" />
<link href="{{ asset('frontend/vendor/OwlCarousel/assets/owl.carousel.css') }}" rel="stylesheet" />
<link href="{{ asset('frontend/vendor/OwlCarousel/assets/owl.theme.default.min.css') }}" rel="stylesheet" />
<link href="{{ asset('frontend/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />
<link href="{{ asset('frontend/vendor/bootstrap-select/dist/css/bootstrap-select.min.css') }}" rel="stylesheet" />
<link href="{{ asset('frontend/css/style.css?v=' . time()) }}" rel="stylesheet" />
<link href="{{ asset('frontend/css/responsive.css?v=' . time()) }}" rel="stylesheet" />
<link href="{{ asset('frontend/css/night-mode.css?v=' . time()) }}" rel="stylesheet" />
<link href="{{ asset('frontend/css/vertical-responsive-menu.css?v=' . time()) }}" rel="stylesheet" />
<style>
    .skip-to-content {
        position: absolute;
        left: -999px;
        top: 8px;
        z-index: 9999;
        padding: 10px 14px;
        background: #111827;
        color: #fff;
        border-radius: 6px;
    }
    .skip-to-content:focus { left: 8px; color: #fff; }
    .marketplace-empty-state { border: 1px dashed #d9dce3; border-radius: 12px; background: #fff; }
    .focus-ring:focus { outline: 3px solid rgba(13,110,253,.35); outline-offset: 2px; }
</style>

