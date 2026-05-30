<x-website.app-layout>
    <x-slot name="title">{{ __('static.Services') }} - {{ config('app.name') }}</x-slot>

    <section class="inner-hero" id="servicesHero">
        <div class="container">
            <div class="breadcrumb">الرئيسية <span>/ خدماتنا</span></div>
            <h1>خدماتنا القانونية</h1>
            <p>مجموعة متكاملة من الخدمات والاستشارات القانونية المصممة بعناية لتلبية احتياجات الأفراد والشركات.</p>
        </div>
    </section>

    <section class="section section-soft" id="servicesDetails">
        <div class="container">
            <div class="sec-head">
                <div class="kicker">مجالات العمل</div>
                <h2>خدمات قانونية مصممة حسب احتياجك</h2>
                <p>اختر الخدمة القانونية المناسبة، وسيتولى فريقنا دراسة ملفك وتحديد المسار الأفضل.</p>
            </div>

            <div class="services-grid">
                @forelse ($services as $service)
                    <article class="service-card">
                        <div class="service-icon"><span class="svg-icon"><i class="{{ $service->icon ?: 'fa-solid fa-scale-balanced' }}"></i></span></div>
                        <h3>{{ $service->title }}</h3>
                        <p>{{ $service->summary }}</p>
                        <a href="{{ route('services.show', $service->slug) }}">تفاصيل الخدمة <span>←</span></a>
                    </article>
                @empty
                    <article class="service-card">
                        <h3>لا توجد خدمات منشورة حالياً</h3>
                        <p>يمكن إضافة الخدمات من لوحة التحكم لتظهر هنا مباشرة.</p>
                    </article>
                @endforelse
            </div>
        </div>
    </section>

    <section class="cta" id="servicesCta">
        <div class="container">
            <h2>غير متأكد من نوع الخدمة؟</h2>
            <p>احجز استشارة عامة وسنساعدك في تحديد المسار القانوني المناسب.</p>
            <a class="btn btn-gold btn-hero-main" href="{{ route('ticket') }}">احجز استشارة الآن <span class="btn-arrow">←</span></a>
        </div>
    </section>
</x-website.app-layout>
