<x-website.app-layout>
    @php
        $setting = fn (string $key, mixed $default = null) => \App\Models\SiteSetting::getValue($key, $default);
        $mapUrl = $setting('map_embed_url');
    @endphp

    <x-slot name="title">
        {{ __('static.Contact Us') }} - {{ config('app.name') }}
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
    <section class="inner-hero" id="contactHero">
        <div class="container">
            <div class="breadcrumb">الرئيسية <span>/ اتصل بنا</span></div>
            <h1>تواصل معنا</h1>
            <p>يسعدنا دائمًا الإجابة على استفساراتك واستقبال ملفاتك القانونية. تواصل معنا مباشرة أو أرسل رسالتك عبر
                النموذج.
            </p>
        </div>
    </section>

    <!-- Contact Grid Section -->
    <section class="section section-soft" id="contactSection">
        <div class="container contact-grid">

            <!-- Contact Message Form -->
            <form class="form-card" id="contactForm" method="POST" action="{{ route('contact.store') }}">
                @csrf
                @include('website.partials.alerts')
                <div class="form-header">
                    <h2>أرسل لنا رسالة سريعة</h2>
                    <p>سيتولى فريق خدمة العملاء مراجعة طلبك وإعادة التواصل معك في أقرب وقت عمل ممكن.</p>
                </div>

                <div class="form-row">
                    <div class="field">
                        <label for="contactName">الاسم الكامل <span class="req">*</span></label>
                        <input id="contactName" name="name" value="{{ old('name') }}" required placeholder="اكتب اسمك الكامل">
                    </div>
                    <div class="field">
                        <label for="contactPhone">رقم الهاتف <span class="req">*</span></label>
                        <input id="contactPhone" name="phone" type="tel" value="{{ old('phone') }}" required placeholder="+970">
                    </div>
                </div>

                <div class="field">
                    <label for="contactEmail">البريد الإلكتروني</label>
                    <input id="contactEmail" name="email" type="email" value="{{ old('email') }}" placeholder="example@email.com">
                </div>

                <div class="field">
                    <label for="contactMessage">الرسالة أو الاستفسار <span class="req">*</span></label>
                    <textarea id="contactMessage" name="message" required placeholder="اكتب تفاصيل استفسارك هنا بوضوح...">{{ old('message') }}</textarea>
                </div>

                <button type="submit" class="btn btn-gold" style="width: 100%;">
                    إرسال الرسالة <span class="btn-arrow">←</span>
                </button>
            </form>

            <!-- Sidebar Contact Information -->
            <aside>
                <div class="contact-card">
                    <span class="svg-icon">
                        <svg viewBox="0 0 24 24">
                            <path
                                d="M22 16.9v3a2 2 0 0 1-2.2 2A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7l.4 2.8a2 2 0 0 1-.5 1.7L7.8 9.4a16 16 0 0 0 6.8 6.8l1.2-1.2a2 2 0 0 1 1.7-.5l2.8.4a2 2 0 0 1 1.7 2Z" />
                        </svg>
                    </span>
                    <div>
                        <h3>الهاتف المباشر</h3>
                        <p dir="ltr">{{ $setting('contact_phone') }}</p>
                    </div>
                </div>

                <div class="contact-card">
                    <span class="svg-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M4 5h16v14H4Z" />
                            <path d="m4 7 8 6 8-6" />
                        </svg>
                    </span>
                    <div>
                        <h3>البريد الإلكتروني</h3>
                        <p>{{ $setting('contact_email') }}</p>
                    </div>
                </div>

                <div class="contact-card">
                    <span class="svg-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 21s7-5.2 7-11a7 7 0 0 0-14 0c0 5.8 7 11 7 11Z" />
                            <circle cx="12" cy="10" r="2.5" />
                        </svg>
                    </span>
                    <div>
                        <h3>عنوان المكتب</h3>
                        <p>{{ $setting('office_address') }}</p>
                    </div>
                </div>

                <!-- Modern Map Representation -->
                @if ($mapUrl)
                    <iframe class="map-box" id="officeMap" src="{{ $mapUrl }}" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="موقع المكتب"></iframe>
                @else
                    <div class="map-box" id="officeMap">
                        <div>
                            <span class="svg-icon">
                                <svg viewBox="0 0 24 24">
                                    <path d="M12 21s7-5.2 7-11a7 7 0 0 0-14 0c0 5.8 7 11 7 11Z" />
                                    <circle cx="12" cy="10" r="2.5" />
                                </svg>
                            </span>
                            <h3>موقع {{ $setting('site_short_name') }}</h3>
                            <p>{{ $setting('map_address') }}</p>
                        </div>
                    </div>
                @endif

                <a class="btn btn-gold" href="{{ route('ticket') }}" style="margin-top: 20px; width: 100%;">
                    <span class="svg-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M4 5h16v16H4Z" />
                            <path d="M8 3v4" />
                            <path d="M16 3v4" />
                            <path d="M4 10h16" />
                        </svg>
                    </span>
                    احجز استشارة قانونية سرية
                </a>
            </aside>

        </div>
    </section>

</x-website.app-layout>
