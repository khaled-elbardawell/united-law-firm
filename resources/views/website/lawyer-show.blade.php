<x-website.app-layout>
    @php
        $photo = $lawyer->photo ? (str_starts_with($lawyer->photo, 'http') ? $lawyer->photo : asset($lawyer->photo)) : null;
        $cvFile = $lawyer->cv_file ? (str_starts_with($lawyer->cv_file, 'http') ? $lawyer->cv_file : asset($lawyer->cv_file)) : null;
        $sections = [
            'experience' => ['title' => 'الخبرات العملية', 'icon' => 'fa-briefcase'],
            'education' => ['title' => 'التعليم والمؤهلات', 'icon' => 'fa-graduation-cap'],
            'certifications' => ['title' => 'الشهادات والدورات', 'icon' => 'fa-certificate'],
            'court_admissions' => ['title' => 'الترافع والاعتمادات', 'icon' => 'fa-gavel'],
            'memberships' => ['title' => 'العضويات المهنية', 'icon' => 'fa-id-badge'],
            'awards' => ['title' => 'الجوائز والإنجازات', 'icon' => 'fa-award'],
        ];
    @endphp

    <x-slot name="title">{{ $lawyer->name }} - {{ \App\Models\SiteSetting::getValue('site_name', config('app.name')) }}</x-slot>

    <x-slot name="head">
        <meta name="description" content="{{ $lawyer->professional_summary ?: $lawyer->bio }}">
    </x-slot>

    <section class="lawyer-profile-hero">
        <div class="container lawyer-profile-hero__grid">
            <div class="lawyer-profile-copy">
                <div class="breadcrumb">الرئيسية <span>/ المحامون / {{ $lawyer->name }}</span></div>
                <span class="role">{{ $lawyer->position }}</span>
                <h1>{{ $lawyer->name }}</h1>
                @if ($lawyer->specialty)
                    <p class="lawyer-profile-specialty">{{ $lawyer->specialty }}</p>
                @endif
                <p>{{ $lawyer->professional_summary ?: $lawyer->bio }}</p>

                <div class="lawyer-profile-actions">
                    <a class="btn btn-gold" href="{{ route('ticket') }}">احجز استشارة</a>
                    @if ($cvFile)
                        <a class="btn btn-outline" href="{{ $cvFile }}" target="_blank" rel="noopener">تحميل CV</a>
                    @endif
                </div>
            </div>

            <aside class="lawyer-profile-card">
                <div class="lawyer-profile-photo">
                    @if ($photo)
                        <img src="{{ $photo }}" alt="{{ $lawyer->name }}">
                    @else
                        <span class="svg-icon"><i class="fa-solid fa-scale-balanced"></i></span>
                    @endif
                </div>
                <div class="lawyer-profile-facts">
                    @if ($lawyer->years_experience)
                        <div><strong>{{ $lawyer->years_experience }}+</strong><span>سنوات خبرة</span></div>
                    @endif
                    @if ($lawyer->bar_number)
                        <div><strong>{{ $lawyer->bar_number }}</strong><span>رقم القيد</span></div>
                    @endif
                    @if ($lawyer->languages)
                        <div><strong>{{ count($lawyer->languages) }}</strong><span>لغات</span></div>
                    @endif
                </div>
            </aside>
        </div>
    </section>

    <section class="section lawyer-cv-section">
        <div class="container lawyer-cv-layout">
            <aside class="lawyer-cv-sidebar">
                <div class="lawyer-cv-box">
                    <h3>بيانات التواصل</h3>
                    @if ($lawyer->email)
                        <a href="mailto:{{ $lawyer->email }}"><i class="fa-solid fa-envelope"></i>{{ $lawyer->email }}</a>
                    @endif
                    @if ($lawyer->phone)
                        <a href="tel:{{ $lawyer->phone }}"><i class="fa-solid fa-phone"></i>{{ $lawyer->phone }}</a>
                    @endif
                    @if ($lawyer->linkedin_url)
                        <a href="{{ $lawyer->linkedin_url }}" target="_blank" rel="noopener"><i class="fa-brands fa-linkedin-in"></i>LinkedIn</a>
                    @endif
                    @if ($lawyer->website_url)
                        <a href="{{ $lawyer->website_url }}" target="_blank" rel="noopener"><i class="fa-solid fa-arrow-up-right-from-square"></i>ملف خارجي</a>
                    @endif
                    @unless ($lawyer->email || $lawyer->phone || $lawyer->linkedin_url || $lawyer->website_url)
                        <p class="muted">يمكن التواصل مع المكتب لحجز موعد مع المحامي.</p>
                    @endunless
                </div>

                @if ($lawyer->tags)
                    <div class="lawyer-cv-box">
                        <h3>مجالات التركيز</h3>
                        <div class="tags">
                            @foreach ($lawyer->tags as $tag)
                                <span>{{ $tag }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($lawyer->languages)
                    <div class="lawyer-cv-box">
                        <h3>اللغات</h3>
                        <ul class="lawyer-cv-list lawyer-cv-list--compact">
                            @foreach ($lawyer->languages as $language)
                                <li>{{ $language }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </aside>

            <main class="lawyer-cv-main">
                <article class="lawyer-cv-panel">
                    <div class="lawyer-cv-panel__head">
                        <i class="fa-solid fa-user-tie"></i>
                        <div>
                            <span>Professional Profile</span>
                            <h2>الملف المهني</h2>
                        </div>
                    </div>
                    <p>{{ $lawyer->professional_summary ?: $lawyer->bio }}</p>
                </article>

                @foreach ($sections as $field => $meta)
                    @if ($lawyer->{$field})
                        <article class="lawyer-cv-panel">
                            <div class="lawyer-cv-panel__head">
                                <i class="fa-solid {{ $meta['icon'] }}"></i>
                                <div>
                                    <span>CV Section</span>
                                    <h2>{{ $meta['title'] }}</h2>
                                </div>
                            </div>
                            <ul class="lawyer-cv-list">
                                @foreach ($lawyer->{$field} as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        </article>
                    @endif
                @endforeach
            </main>
        </div>
    </section>

    @if ($relatedLawyers->isNotEmpty())
        <section class="section section-soft">
            <div class="container">
                <div class="sec-head">
                    <div class="kicker">فريق العمل</div>
                    <h2>محامون آخرون من الفريق</h2>
                </div>
                <div class="lawyers-grid">
                    @foreach ($relatedLawyers as $related)
                        <a class="lawyer-card" href="{{ route('lawyers.show', $related) }}">
                            <div class="lawyer-top">
                                @if ($related->photo)
                                    <img src="{{ str_starts_with($related->photo, 'http') ? $related->photo : asset($related->photo) }}" alt="{{ $related->name }}">
                                @else
                                    <span class="svg-icon"><i class="fa-solid fa-scale-balanced"></i></span>
                                @endif
                            </div>
                            <div class="lawyer-body">
                                <span class="role">{{ $related->position }}</span>
                                <h3>{{ $related->name }}</h3>
                                <p>{{ $related->bio }}</p>
                                <span class="lawyer-card-link">عرض السيرة المهنية <i class="fa-solid fa-arrow-left"></i></span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</x-website.app-layout>
