<x-website.app-layout>
    @php
        $setting = fn (string $key, mixed $default = null) => \App\Models\SiteSetting::getValue($key, $default);
    @endphp

    <x-slot name="title">{{ __('static.Home') }} - {{ config('app.name') }}</x-slot>

    <section class="hero hero--home" id="homeHero">
        <div class="hero--home-body">
            <div class="container">
                <div class="hero-content">
                    <h1>{{ $setting('hero_title') }} <span>{{ $setting('hero_highlight') }}</span></h1>
                    <p>{{ $setting('hero_description') }}</p>
                    <div class="hero-actions">
                        <a class="btn btn-gold btn-hero-main" href="{{ route('ticket') }}">احجز استشارة <span class="btn-arrow">←</span></a>
                        <a class="btn btn-outline btn-hero-secondary" href="{{ route('services') }}">تعرف على خدماتنا <span class="btn-arrow">←</span></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="hero-card hero-card--home" id="homeStats">
            <div class="stat"><span class="svg-icon"><i class="fa-solid fa-scale-balanced"></i></span><strong>{{ $setting('stat_cases_value') }}</strong><span>{{ $setting('stat_cases_label') }}</span></div>
            <div class="stat"><span class="svg-icon"><i class="fa-solid fa-award"></i></span><strong>{{ $setting('stat_experience_value') }}</strong><span>{{ $setting('stat_experience_label') }}</span></div>
            <div class="stat"><span class="svg-icon"><i class="fa-solid fa-user-tie"></i></span><strong>{{ $setting('stat_lawyers_value') }}</strong><span>{{ $setting('stat_lawyers_label') }}</span></div>
            <div class="stat"><span class="svg-icon"><i class="fa-solid fa-chart-line"></i></span><strong>{{ $setting('stat_success_value') }}</strong><span>{{ $setting('stat_success_label') }}</span></div>
        </div>
    </section>

    <section class="section section-soft" id="homeServices">
        <div class="container">
            <div class="sec-head">
                <div class="kicker">خدماتنا القانونية</div>
                <h2>حلول قانونية متكاملة لمختلف احتياجاتك</h2>
                <p>نقدم مجموعة واسعة من الخدمات القانونية المتخصصة للأفراد والشركات، ويتم التحكم بهذه البطاقات مباشرة من لوحة التحكم.</p>
            </div>

            <div class="services-grid services-grid--home">
                @forelse ($services as $service)
                    <article class="service-card service-card--home">
                        <div class="service-icon"><span class="svg-icon"><i class="{{ $service->icon ?: 'fa-solid fa-scale-balanced' }}"></i></span></div>
                        <h3>{{ $service->title }}</h3>
                        <p>{{ $service->summary }}</p>
                        <a href="{{ route('services.show', $service->slug) }}">تفاصيل الخدمة <span>←</span></a>
                    </article>
                @empty
                    <article class="service-card service-card--home">
                        <div class="service-icon"><span class="svg-icon"><i class="fa-solid fa-scale-balanced"></i></span></div>
                        <h3>لا توجد خدمات منشورة</h3>
                        <p>أضف الخدمات من لوحة التحكم لتظهر هنا تلقائيا.</p>
                        <a href="{{ route('services') }}">عرض الخدمات <span>←</span></a>
                    </article>
                @endforelse
            </div>
        </div>
    </section>

    <section class="section" id="homeFeatures">
        <div class="container">
            <div class="sec-head">
                <div class="kicker">ميزات المكتب</div>
                <h2>لماذا تختار مكتب المتحدة للمحاماة؟</h2>
                <p>نعمل بمعايير مهنية صارمة ونقدم رعاية قانونية واضحة وسرية لجميع عملائنا.</p>
            </div>

            <div class="feature-grid">
                <div class="feature"><span class="svg-icon"><i class="fa-solid fa-shield-halved"></i></span><strong>سرية تامة</strong><p>حماية كاملة لجميع معلومات وملفات العملاء بسرية مهنية عالية.</p></div>
                <div class="feature"><span class="svg-icon"><i class="fa-solid fa-star"></i></span><strong>نتائج قوية</strong><p>استراتيجية مخصصة ودراسة تفصيلية لكل قضية لتحقيق أفضل النتائج.</p></div>
                <div class="feature"><span class="svg-icon"><i class="fa-solid fa-users"></i></span><strong>فريق متخصص</strong><p>محامون ومستشارون بخبرة عميقة في مختلف فروع القانون.</p></div>
                <div class="feature"><span class="svg-icon"><i class="fa-solid fa-arrows-rotate"></i></span><strong>متابعة مستمرة</strong><p>تواصل واضح وتقارير دورية لكل خطوة وتطور في الملف القانوني.</p></div>
            </div>
        </div>
    </section>

    <section class="cta" id="homeCta">
        <div class="container">
            <h2>{{ $setting('cta_title') }}</h2>
            <p>{{ $setting('cta_description') }}</p>
            <a class="btn btn-gold btn-hero-main" href="{{ route('ticket') }}">احجز استشارة الآن <span class="btn-arrow">←</span></a>
        </div>
    </section>
</x-website.app-layout>
