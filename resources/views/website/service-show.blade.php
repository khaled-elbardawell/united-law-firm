<x-website.app-layout>
    <x-slot name="title">{{ $service->title }} - {{ \App\Models\SiteSetting::getValue('site_name', config('app.name')) }}</x-slot>

    <section class="inner-hero" id="serviceHero">
        <div class="container">
            <div class="breadcrumb">
                <a href="{{ route('home') }}">الرئيسية</a>
                <span>/</span>
                <a href="{{ route('services') }}">خدماتنا</a>
                <span>/ {{ $service->title }}</span>
            </div>
            <h1>{{ $service->title }}</h1>
            <p>{{ $service->summary }}</p>
        </div>
    </section>

    <section class="section section-soft" id="serviceDetails">
        <div class="container service-detail-grid">
            <article class="service-detail-card">
                <div class="service-detail-icon">
                    <i class="{{ $service->icon ?: 'fa-solid fa-scale-balanced' }}"></i>
                </div>
                <h2>{{ $service->title }}</h2>
                <div class="service-detail-content">
                    {!! nl2br(e($service->description ?: $service->summary)) !!}
                </div>
            </article>

            <aside class="service-detail-side">
                <div class="service-cta-card">
                    <h3>هل تحتاج هذه الخدمة؟</h3>
                    <p>احجز استشارة قانونية وسيتم توجيه طلبك للفريق المختص بهذا المجال.</p>
                    <a class="btn btn-gold" href="{{ route('ticket', ['service' => $service->title]) }}">احجز استشارة</a>
                    <a class="btn btn-outline-dark" href="{{ route('contact') }}">تواصل معنا</a>
                </div>

                @if ($relatedServices->isNotEmpty())
                    <div class="service-related-card">
                        <h3>خدمات أخرى</h3>
                        @foreach ($relatedServices as $related)
                            <a href="{{ route('services.show', $related->slug) }}">
                                <i class="{{ $related->icon ?: 'fa-solid fa-scale-balanced' }}"></i>
                                <span>{{ $related->title }}</span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </aside>
        </div>
    </section>
</x-website.app-layout>
