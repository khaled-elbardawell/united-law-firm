<x-website.app-layout>
    <x-slot name="title">{{ __('static.FAQ') }} - {{ config('app.name') }}</x-slot>

    <section class="inner-hero" id="faqHero">
        <div class="container">
            <div class="breadcrumb">الرئيسية <span>/ الأسئلة الشائعة</span></div>
            <h1>الأسئلة الشائعة</h1>
            <p>إجابات واضحة على أهم التساؤلات حول خدماتنا وطريقة التواصل وحجز الاستشارات.</p>
        </div>
    </section>

    <section class="section" id="faqAccordionSection">
        <div class="container">
            <div class="sec-head">
                <div class="kicker">مركز المساعدة</div>
                <h2>إجابات واضحة على أهم استفساراتكم</h2>
                <p>نحرص على تبسيط الإجراءات القانونية قبل البدء في تداول القضايا.</p>
            </div>

            <div class="faq-wrap">
                @forelse ($faqs as $faq)
                    <div class="faq-item">
                        <button class="faq-q" type="button">{{ $faq->question }}<span>+</span></button>
                        <div class="faq-a">{{ $faq->answer }}</div>
                    </div>
                @empty
                    <div class="faq-item open">
                        <button class="faq-q" type="button">لا توجد أسئلة منشورة حالياً<span>+</span></button>
                        <div class="faq-a">يمكن إضافة الأسئلة الشائعة من لوحة التحكم.</div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="cta" id="faqCta">
        <div class="container">
            <h2>لم تجد إجابة واضحة؟</h2>
            <p>تواصل معنا مباشرة أو احجز استشارة سريعة مع أحد محامينا.</p>
            <a class="btn btn-gold btn-hero-main" href="{{ route('ticket') }}">احجز استشارة الآن <span class="btn-arrow">←</span></a>
        </div>
    </section>
</x-website.app-layout>
