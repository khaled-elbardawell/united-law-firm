@php
    $siteName = \App\Models\SiteSetting::getValue('site_short_name', \App\Models\SiteSetting::getValue('site_name', config('app.name')));
    $tagline = \App\Models\SiteSetting::getValue('site_tagline', 'UNITED Legal Firm');
    $logo = \App\Models\SiteSetting::getValue('site_logo', 'assets/logo.png');
    $logoUrl = str_starts_with($logo, 'http') ? $logo : asset($logo);
    $phone = \App\Models\SiteSetting::getValue('contact_phone');
    $email = \App\Models\SiteSetting::getValue('contact_email');
    $address = \App\Models\SiteSetting::getValue('office_address');
    $description = \App\Models\SiteSetting::getValue('footer_description');
    $copyright = \App\Models\SiteSetting::getValue('copyright_text');
    $socials = [
        'LinkedIn' => ['url' => \App\Models\SiteSetting::getValue('social_linkedin'), 'icon' => 'fa-brands fa-linkedin-in'],
        'Facebook' => ['url' => \App\Models\SiteSetting::getValue('social_facebook'), 'icon' => 'fa-brands fa-facebook-f'],
        'X' => ['url' => \App\Models\SiteSetting::getValue('social_x'), 'icon' => 'fa-brands fa-x-twitter'],
        'Instagram' => ['url' => \App\Models\SiteSetting::getValue('social_instagram'), 'icon' => 'fa-brands fa-instagram'],
    ];
@endphp

<footer class="footer" id="siteFooter">
    <div class="container footer-grid">
        <div class="footer-about">
            <a href="{{ route('home') }}" class="brand footer-brand">
                <img src="{{ $logoUrl }}" alt="{{ $siteName }}">
                <div>
                    <strong>{{ $siteName }}</strong>
                    <span>{{ $tagline }}</span>
                </div>
            </a>
            <p>{{ $description }}</p>

            <div class="socials">
                @foreach ($socials as $name => $social)
                    @if ($social['url'])
                        <a href="{{ $social['url'] }}" aria-label="{{ $name }}" target="_blank" rel="noopener">
                            <i class="{{ $social['icon'] }}"></i>
                        </a>
                    @endif
                @endforeach
            </div>
        </div>

        <div class="footer-links">
            <h4>روابط سريعة</h4>
            <a href="{{ route('about') }}">عن المكتب</a>
            <a href="{{ route('lawyers') }}">المحامون</a>
            <a href="{{ route('services') }}">خدماتنا</a>
            <a href="{{ route('faq') }}">الأسئلة الشائعة</a>
        </div>

        <div class="footer-links">
            <h4>مجالات العمل</h4>
            <a href="{{ route('services') }}">القضايا التجارية</a>
            <a href="{{ route('services') }}">القضايا المدنية</a>
            <a href="{{ route('services') }}">القضايا الجنائية</a>
            <a href="{{ route('ticket') }}">احجز استشارة</a>
        </div>

        <div class="footer-links">
            <h4>تواصل معنا</h4>
            @if ($phone)<p class="footer-contact-item"><span class="svg-icon"><i class="fa-solid fa-phone"></i></span> <span dir="ltr">{{ $phone }}</span></p>@endif
            @if ($email)<p class="footer-contact-item"><span class="svg-icon"><i class="fa-solid fa-envelope"></i></span> {{ $email }}</p>@endif
            @if ($address)<p class="footer-contact-item"><span class="svg-icon"><i class="fa-solid fa-location-dot"></i></span> {{ $address }}</p>@endif
            <a href="{{ route('contact') }}">صفحة التواصل</a>
        </div>
    </div>

    <div class="container copy" id="footerCopy">
        {{ $copyright }}
    </div>
</footer>
