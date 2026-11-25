@props([
    'title' => null,
    'description' => null,
    'image' => null,
    'type' => 'website',
    'canonical' => null,
    'fullTitle' => null, // exact <title>, used as-is (home page)
    'keywords' => null,
    'firebase' => false, // legacy Firebase Analytics, home page only (unchanged behaviour)
])
@php
    $locale = app()->getLocale();
    $rtl = $locale === 'ar';
    $siteName = config('site.name');
    $pageTitle = $fullTitle ?: ($title ? $title.' | '.$siteName : __('site.seo.home_title'));
    $pageDescription = \Illuminate\Support\Str::limit(strip_tags($description ?: __('site.meta_description')), 300);
    $pageKeywords = $keywords ?: __('site.seo.keywords');
    $pageImage = $image ?: asset('assets/images/logo.png');

    // One URL per language (?lang=ar / ?lang=en) so search engines index both.
    // Only query values that change the content (category) are kept.
    $baseUrl = rtrim(url()->current(), '/').(request()->path() === '/' ? '/' : '');
    $keep = array_filter(request()->only('category'), 'filled');
    $langUrl = fn ($lang) => $baseUrl.'?'.http_build_query($keep + ['lang' => $lang]);
    $pageUrl = $canonical ?: $langUrl($locale);

    $organization = array_filter([
        '@type' => 'GeneralContractor',
        '@id' => url('/').'#organization',
        'name' => $siteName,
        'alternateName' => config('site.alternate_names'),
        'url' => url('/'),
        'logo' => asset('assets/images/logo.png'),
        'image' => asset('assets/images/logo.png'),
        'description' => __('site.meta_description'),
        'slogan' => __('site.seo.brand_line'),
        'email' => $siteContact->email ?? null,
        'telephone' => ((array) ($siteContact->phone_numbers ?? []))[0] ?? null,
        'address' => ['@type' => 'PostalAddress', 'addressCountry' => 'EG', 'addressRegion' => $rtl ? 'القاهرة' : 'Cairo'],
        'areaServed' => array_map(fn ($a) => ['@type' => 'City', 'name' => $a], config('site.areas.'.$locale, [])),
        'knowsAbout' => __('site.seo.services_list'),
        'knowsLanguage' => ['ar', 'en'],
        'hasOfferCatalog' => [
            '@type' => 'OfferCatalog',
            'name' => __('site.services.title'),
            'itemListElement' => array_map(fn ($s) => ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => $s]], __('site.seo.services_list')),
        ],
        'sameAs' => config('site.same_as') ?: null,
    ]);
    $website = [
        '@type' => 'WebSite',
        '@id' => url('/').'#website',
        'name' => $siteName,
        'alternateName' => config('site.alternate_names'),
        'url' => url('/'),
        'inLanguage' => ['ar', 'en'],
        'publisher' => ['@id' => url('/').'#organization'],
    ];
    $schemaJson = json_encode(['@'.'context' => 'https://schema.org', '@graph' => [$organization, $website]], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP);
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $rtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    <meta name="keywords" content="{{ $pageKeywords }}">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1">
    <meta name="author" content="{{ $siteName }}">
    <link rel="canonical" href="{{ $pageUrl }}">
    <link rel="alternate" hreflang="ar" href="{{ $langUrl('ar') }}">
    <link rel="alternate" hreflang="en" href="{{ $langUrl('en') }}">
    <link rel="alternate" hreflang="x-default" href="{{ $baseUrl.($keep ? '?'.http_build_query($keep) : '') }}">
    <link rel="sitemap" type="application/xml" href="{{ url('/sitemap.xml') }}">
    @if (config('site.google_verification'))
        <meta name="google-site-verification" content="{{ config('site.google_verification') }}">
    @endif
    @if (config('site.bing_verification'))
        <meta name="msvalidate.01" content="{{ config('site.bing_verification') }}">
    @endif

    <meta property="og:type" content="{{ $type }}">
    <meta property="og:url" content="{{ $pageUrl }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:image" content="{{ $pageImage }}">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:locale" content="{{ $rtl ? 'ar_EG' : 'en_US' }}">
    <meta property="og:locale:alternate" content="{{ $rtl ? 'en_US' : 'ar_EG' }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDescription }}">
    <meta name="twitter:image" content="{{ $pageImage }}">

    <meta name="theme-color" content="#f3f1ed">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('icon-512.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

    <script type="application/ld+json">{!! $schemaJson !!}</script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @include('partials.site.analytics', ['pageTitle' => $pageTitle, 'pageUrl' => $pageUrl])

    {{ $head ?? '' }}
</head>
<body class="min-h-dvh">
    <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:start-3 focus:z-[60] focus:bg-charcoal focus:px-4 focus:py-3 focus:text-white">{{ __('site.skip') }}</a>

    <x-site.header />

    <main id="main" tabindex="-1" class="outline-none">
        {{ $slot }}
    </main>

    <x-site.footer />

    @if ($firebase && config('site.firebase_analytics'))
        @include('partials.site.firebase')
    @endif
</body>
</html>
