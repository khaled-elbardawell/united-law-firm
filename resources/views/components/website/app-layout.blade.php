<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('assets/logo.png') }}" sizes="32x32" />
    @php
        $routeKey = request()->route()?->getName();
        $seo = null;
        if ($routeKey && \Illuminate\Support\Facades\Schema::hasTable('seo_settings')) {
            $seo = \App\Models\SeoSetting::where('page_key', $routeKey)->first();
        }
        $siteName = \App\Models\SiteSetting::getValue('site_name', config('app.name'));
        $seoDescription = \App\Models\SiteSetting::getValue('seo_default_description', __('static.seo_description'));
        $seoKeywords = \App\Models\SiteSetting::getValue('seo_default_keywords', __('static.seo_keywords'));
        $seoAuthor = \App\Models\SiteSetting::getValue('seo_author', $siteName);
        $whatsapp = \App\Models\SiteSetting::getValue('whatsapp_number');
        $whatsappDigits = preg_replace('/\D+/', '', (string) $whatsapp);
    @endphp

    <title>{{ $seo?->title ?: $title ?? $siteName }}</title>
    @if ($seo)
        <meta name="description" content="{{ $seo->description }}">
        <meta name="keywords" content="{{ $seo->keywords }}">
        <meta name="author" content="{{ $seoAuthor }}">
        <meta name="robots" content="{{ $seo->is_indexable ? 'index,follow' : 'noindex,nofollow' }}">
        @if ($seo->canonical_url)
            <link rel="canonical" href="{{ $seo->canonical_url }}">
        @endif
        @if ($seo->og_image)
            <meta property="og:image" content="{{ $seo->og_image }}">
        @endif
    @elseif (isset($head))
        {!! $head !!}
    @else
        <meta name="description" content="{{ $seoDescription }}">
        <meta name="keywords" content="{{ $seoKeywords }}">
        <meta name="author" content="{{ $seoAuthor }}">
    @endif

  
     <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/css/style.css'])
    {!! $css ?? '' !!}
</head>

<body class="page-home">
    <x-website.header />

    <main id="mainContent">
        {{ $slot }}
    </main>

    <x-website.footer />
    @if ($whatsappDigits)
        <a href="https://wa.me/{{ $whatsappDigits }}" class="float-whatsapp" aria-label="تواصل معنا عبر واتساب"
            target="_blank" rel="noopener">واتساب</a>
    @endif
    <button class="scroll-top-button" type="button" aria-label="العودة إلى أعلى الصفحة">
        <i class="fa-solid fa-arrow-up"></i>
    </button>

    @vite(['resources/js/app.js', 'resources/js/script.js'])
    {!! $js ?? '' !!}
</body>

</html>
