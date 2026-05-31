@php
    $siteName = \App\Models\SiteSetting::getValue(
        'site_short_name',
        \App\Models\SiteSetting::getValue('site_name', config('app.name')),
    );
    $tagline = \App\Models\SiteSetting::getValue('site_tagline', 'UNITED Legal Firm');
    $logo = \App\Models\SiteSetting::getValue('site_logo', 'assets/logo.png');
    $logoUrl = str_starts_with($logo, 'http') ? $logo : asset($logo);
@endphp

<header class="site-header site-header--hero" id="siteHeader">
    <div class="container nav-wrap">
        <a href="{{ route('home') }}" class="brand" id="brandLogo">
            <img src="{{ $logoUrl }}" alt="{{ $siteName }}">
            <div>
                <strong>{{ $siteName }}</strong>
                <span>{{ $tagline }}</span>
            </div>
        </a>

        <nav class="nav-links" id="mainNav">
            <a class="{{ request()->routeIs('home') ? 'active' : '' }}"
                href="{{ route('home') }}">{{ __('static.Home') }}</a>
            <a class="{{ request()->routeIs('about') ? 'active' : '' }}"
                href="{{ route('about') }}">{{ __('static.About Us') }}</a>
            <a class="{{ request()->routeIs('services') ? 'active' : '' }}"
                href="{{ route('services') }}">{{ __('static.Services') }}</a>
            <a class="{{ request()->routeIs('lawyers*') ? 'active' : '' }}"
                href="{{ route('lawyers') }}">{{ __('static.Lawyers') }}</a>
            <div class="nav-dropdown {{ request()->routeIs('training-courses.*') ? 'active' : '' }}">
                <button type="button" aria-haspopup="true" aria-expanded="false">
                    الدورات التدريبية <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="nav-dropdown-menu">
                    <a href="{{ route('training-courses.current') }}">الدورات التدريبية الجديدة</a>
                    <a href="{{ route('training-courses.past') }}">الدورات التدريبية السابقة</a>
                </div>
            </div>
            {{-- <a class="{{ request()->routeIs('faq') ? 'active' : '' }}" href="{{ route('faq') }}">{{ __('static.FAQ') }}</a> --}}
            <a class="{{ request()->routeIs('blog.*') ? 'active' : '' }}" href="{{ route('blog.index') }}">المدونة</a>
            <a class="{{ request()->routeIs('contact') ? 'active' : '' }}"
                href="{{ route('contact') }}">{{ __('static.Contact Us') }}</a>
            <a class="nav-ticket {{ request()->routeIs('ticket') ? 'active' : '' }}" href="{{ route('ticket') }}">
                <span class="svg-icon">
                    <i class="fa-solid fa-calendar-check"></i>
                </span>
                {{ __('static.Book Consultation') }}
            </a>
        </nav>

        <button class="menu-btn" aria-label="القائمة" aria-expanded="false" id="menuToggle">☰</button>
    </div>
</header>
