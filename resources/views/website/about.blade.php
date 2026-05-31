<x-website.app-layout>
    @php
        $setting = fn (string $key, mixed $default = null) => \App\Models\SiteSetting::getValue($key, $default);
    @endphp

    <x-slot name="title">
        {{ __('static.About Us') }} - {{ config('app.name') }}
    </x-slot>

    <x-slot name="head">
        <!-- SEO Best Practices -->
        <meta name="description"
            content="مكتب المتحدة للمحاماة والاستشارات القانونية يقدم حلولاً قانونية راقية للأفراد والشركات بخبرة عالية وسرية تامة واهتمام بأدق التفاصيل.">
        <meta name="keywords"
            content="محامي غزة، المتحدة للمحاماة، استشارات قانونية، قضايا تجارية، قضايا جنائية، تحكيم عقود">
        <meta name="author" content="المتحدة للمحاماة">
    </x-slot>


    <!-- Inner Page Hero -->
    <section class="inner-hero" id="aboutHero">
        <div class="container">
            <div class="breadcrumb">الرئيسية <span>/ عن المكتب</span></div>
            <h1>عن المكتب</h1>
            <p>خبرة قانونية راسخة، التزام مهني، وسرية تامة — نبني علاقة ثقة راسخة مع كل عميل.</p>
        </div>
    </section>

    <!-- About Section -->
    <section class="section" id="aboutOverview">
        <div class="container about-grid">
            <div>
                <div class="kicker">من نحن</div>
                <h2 class="title">{{ $setting('about_overview_title', 'خبرة قانونية راقية برؤية عصرية') }}</h2>
                @foreach (preg_split('/\R{2,}/u', trim((string) $setting('about_overview_description', ''))) as $paragraph)
                    @continue(trim($paragraph) === '')
                    <p class="muted" style="margin-bottom: {{ $loop->last ? '24px' : '18px' }};">{{ $paragraph }}</p>
                @endforeach

                <div class="about-stats">
                    <div class="about-stat">
                        <strong>{{ $setting('stat_experience_value') }}</strong>
                        <span>{{ $setting('stat_experience_label') }}</span>
                    </div>
                    <div class="about-stat">
                        <strong>{{ $setting('stat_cases_value') }}</strong>
                        <span>{{ $setting('stat_cases_label') }}</span>
                    </div>
                    <div class="about-stat">
                        <strong>{{ $setting('stat_success_value') }}</strong>
                        <span>{{ $setting('stat_success_label') }}</span>
                    </div>
                </div>

                <a class="btn btn-dark" href="{{ route('contact') }}">تواصل معنا الآن</a>
            </div>

            <div class="office-frame" id="officeFrame" data-label="{{ $setting('office_frame_label') }}"></div>
        </div>
    </section>

    @include('website.partials.trusted-clients', ['clients' => $clients ?? collect()])

    <!-- Values Section -->
    <section class="section section-soft" id="aboutValues">
        <div class="container">
            <div class="sec-head">
                <div class="kicker">قيم المكتب</div>
                <h2>لماذا مكتب المتحدة للمحاماة؟</h2>
                <p>نلتزم بمجموعة من القيم الأساسية التي تمثل دستور عملنا اليومي وتضمن تميز خدماتنا القانونية.</p>
            </div>

            <div class="values">
                <div class="value-card">
                    <span class="svg-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 3 20 6v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6Z" />
                            <path d="m8 12 3 3 5-6" />
                        </svg>
                    </span>
                    <h3>النزاهة والسرية</h3>
                    <p class="muted">تعامل مسؤول، أخلاقي وسري للغاية مع جميع الملفات والقضايا والمستندات الخاصة
                        بعملائنا.</p>
                </div>

                <div class="value-card">
                    <span class="svg-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="m14 5 5 5" />
                            <path d="m6 13 5 5" />
                            <path d="m8 11 6-6 5 5-6 6Z" />
                            <path d="m2 22 7-7" />
                            <path d="M14 22H4" />
                        </svg>
                    </span>
                    <h3>استراتيجية قانونية</h3>
                    <p class="muted">تحليل قانوني دقيق وعميق وبناء مسار قانوني مخصص ومبتكر لكل قضية وملف.</p>
                </div>

                <div class="value-card">
                    <span class="svg-icon">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="8" r="5" />
                            <path d="m8.5 12.5-1.5 8 5-3 5 3-1.5-8" />
                        </svg>
                    </span>
                    <h3>جودة ونتائج</h3>
                    <p class="muted">تركيز مطلق على الجودة المهنية الفائقة وتحقيق أفضل مخرجات ونتائج واقعية ممكنة.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Inner Page CTA -->
    <section class="cta" id="aboutCta">
        <div class="container">
            <h2>هل تحتاج استشارة قانونية تضمن حقوقك؟</h2>
            <p>احجز استشارتك الآن مع أحد محامينا المتخصصين لدراسة ملفك وتوجيهك للمسار القانوني الأسلم.</p>
            <a class="btn btn-gold btn-hero-main" href="{{ route('ticket') }}">
                احجز استشارة <span class="btn-arrow">←</span>
            </a>
        </div>
    </section>

</x-website.app-layout>
