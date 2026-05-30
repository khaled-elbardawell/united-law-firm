<x-website.app-layout>
    <x-slot name="title">{{ __('static.Lawyers') }} - {{ config('app.name') }}</x-slot>

    <section class="inner-hero" id="lawyersHero">
        <div class="container">
            <div class="breadcrumb">الرئيسية <span>/ المحامون</span></div>
            <h1>فريق المحامين</h1>
            <p>نخبة متخصصة من المحامين والمستشارين القانونيين لخدمة ملفاتكم بأعلى درجات المهنية.</p>
        </div>
    </section>

    <section class="section section-soft" id="lawyersList">
        <div class="container">
            <div class="sec-head">
                <div class="kicker">فريق العمل</div>
                <h2>محامون متخصصون بخبرات قانونية راسخة</h2>
                <p>تضم المتحدة فريقاً متعدد التخصصات للتعامل مع أدق القضايا والملفات.</p>
            </div>

            <div class="lawyers-grid">
                @forelse ($lawyers as $lawyer)
                    <article class="lawyer-card">
                        <div class="lawyer-top">
                            @if ($lawyer->photo)
                                <img src="{{ str_starts_with($lawyer->photo, 'http') ? $lawyer->photo : asset($lawyer->photo) }}" alt="{{ $lawyer->name }}">
                            @else
                                <span class="svg-icon"><i class="fa-solid fa-scale-balanced"></i></span>
                            @endif
                        </div>
                        <div class="lawyer-body">
                            <span class="role">{{ $lawyer->position }}</span>
                            <h3>{{ $lawyer->name }}</h3>
                            <p>{{ $lawyer->bio }}</p>
                            @if ($lawyer->tags)
                                <div class="tags">
                                    @foreach ($lawyer->tags as $tag)
                                        <span>{{ $tag }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </article>
                @empty
                    <article class="lawyer-card">
                        <div class="lawyer-body">
                            <h3>لا يوجد محامون منشورون حالياً</h3>
                            <p>يمكن إضافة الفريق من لوحة التحكم.</p>
                        </div>
                    </article>
                @endforelse
            </div>
        </div>
    </section>

    <section class="cta" id="lawyersCta">
        <div class="container">
            <h2>هل تحتاج إلى تمثيل قانوني متخصص؟</h2>
            <p>احجز استشارتك وسيقوم منسق المكتب بتوجيهك للمحامي الأنسب.</p>
            <a class="btn btn-gold btn-hero-main" href="{{ route('ticket') }}">احجز استشارة <span class="btn-arrow">←</span></a>
        </div>
    </section>
</x-website.app-layout>
