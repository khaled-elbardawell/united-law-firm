<x-website.app-layout>
    @php
        $setting = fn (string $key, mixed $default = null) => \App\Models\SiteSetting::getValue($key, $default);
        $whatsappDigits = preg_replace('/\D+/', '', (string) $setting('whatsapp_number'));
    @endphp

    <x-slot name="title">
        {{ __('static.Ticket') }} - {{ config('app.name') }}
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
    <section class="inner-hero inner-hero--ticket" id="ticketHero">
        <div class="container">
            <div class="breadcrumb">الرئيسية <span>/ احجز استشارة</span></div>
            <h1>احجز استشارة قانونية</h1>
            <p>املأ النموذج التفاعلي التالي، وسيتواصل معك المحامي المختص بسرية تامة لترتيب تفاصيل الموعد.</p>
        </div>
    </section>

    <!-- Consultation form & Info section -->
    <section class="section section-ticket" id="ticketSection">
        <div class="container ticket-grid">

            <!-- Multi-Step Interactive Form Card -->
            <form class="form-card premium-ticket" id="consultForm" method="POST" action="{{ route('ticket.store') }}" enctype="multipart/form-data" novalidate>
                @csrf
                @include('website.partials.alerts')
                <div class="ticket-header">
                    <div class="ticket-header-icon">
                        <span class="svg-icon">
                            <svg viewBox="0 0 24 24">
                                <path d="M4 5h16v16H4Z" />
                                <path d="M8 3v4" />
                                <path d="M16 3v4" />
                                <path d="M4 10h16" />
                            </svg>
                        </span>
                    </div>
                    <div>
                        <h2>نموذج طلب استشارة سرية</h2>
                        <p>بياناتك محمية بالكامل بموجب قانون المحاماة وسرية المهنة المقدسة.</p>
                    </div>
                </div>

                <!-- Wizard Step Indicator Progress Bar -->
                <div class="progress-steps" id="wizardProgress">
                    <div class="progress-step is-active" id="progressStep1"><b>1</b> بيانات التواصل</div>
                    <div class="progress-step" id="progressStep2"><b>2</b> نوع الخدمة والموعد</div>
                    <div class="progress-step" id="progressStep3"><b>3</b> تفاصيل الاستشارة</div>
                </div>

                <!-- STEP 1: CONTACT INFORMATION -->
                <div class="form-section is-active" id="step1Section">
                    <h3 class="form-section-title">
                        <span class="svg-icon">
                            <svg viewBox="0 0 24 24">
                                <circle cx="12" cy="8" r="4" />
                                <path d="M5 21a7 7 0 0 1 14 0" />
                            </svg>
                        </span>
                        بيانات التواصل الأساسية
                    </h3>

                    <div class="form-row">
                        <div class="field">
                            <label for="fullName">الاسم الكامل <span class="req">*</span></label>
                            <div class="input-wrap">
                                <span class="svg-icon">
                                    <svg viewBox="0 0 24 24">
                                        <circle cx="12" cy="8" r="4" />
                                        <path d="M5 21a7 7 0 0 1 14 0" />
                                    </svg>
                                </span>
                                <input id="fullName" name="name" value="{{ old('name') }}" required placeholder="اكتب اسمك الكامل ثلاثياً">
                            </div>
                        </div>

                        <div class="field">
                            <label for="phone">رقم الهاتف <span class="req">*</span></label>
                            <div class="input-wrap">
                                <span class="svg-icon">
                                    <svg viewBox="0 0 24 24">
                                        <path
                                            d="M22 16.9v3a2 2 0 0 1-2.2 2A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7l.4 2.8a2 2 0 0 1-.5 1.7L7.8 9.4a16 16 0 0 0 6.8 6.8l1.2-1.2a2 2 0 0 1 1.7-.5l2.8.4a2 2 0 0 1 1.7 2Z" />
                                    </svg>
                                </span>
                                <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" required
                                    placeholder="{{ $setting('contact_phone', '+970 59 XXX XXXX') }}">
                            </div>
                        </div>
                    </div>

                    <div class="field">
                        <label for="email">البريد الإلكتروني</label>
                        <div class="input-wrap">
                            <span class="svg-icon">
                                <svg viewBox="0 0 24 24">
                                    <path d="M4 5h16v14H4Z" />
                                    <path d="m4 7 8 6 8-6" />
                                </svg>
                            </span>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="example@email.com">
                        </div>
                    </div>
                </div>

                <!-- STEP 2: SERVICE DETAILS -->
                <div class="form-section" id="step2Section">
                    <h3 class="form-section-title">
                        <span class="svg-icon">
                            <svg viewBox="0 0 24 24">
                                <path d="M10 6V5a2 2 0 0 1 2-2h0a2 2 0 0 1 2 2v1" />
                                <path d="M3 9a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2Z" />
                                <path d="M3 13h18" />
                            </svg>
                        </span>
                        نوع الخدمة وتنسيق الموعد
                    </h3>

                    <div class="form-row">
                        <div class="field">
                            <label for="service">نوع الخدمة القانونية <span class="req">*</span></label>
                            <div class="input-wrap">
                                <span class="svg-icon">
                                    <svg viewBox="0 0 24 24">
                                        <path d="M10 6V5a2 2 0 0 1 2-2h0a2 2 0 0 1 2 2v1" />
                                        <path
                                            d="M3 9a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2Z" />
                                        <path d="M3 13h18" />
                                    </svg>
                                </span>
                                <select id="service" name="service" required>
                                    <option value="">اختر مجال الخدمة</option>
                                    @foreach ($services as $serviceItem)
                                        <option value="{{ $serviceItem->title }}" @selected(old('service', request('service')) === $serviceItem->title)>{{ $serviceItem->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="field">
                            <label for="preferredDate">الموعد المفضل للمقابلة</label>
                            <div class="input-wrap">
                                <span class="svg-icon">
                                    <svg viewBox="0 0 24 24">
                                        <path d="M4 5h16v16H4Z" />
                                        <path d="M8 3v4" />
                                        <path d="M16 3v4" />
                                        <path d="M4 10h16" />
                                    </svg>
                                </span>
                                <input id="preferredDate" name="preferred_date" type="date" value="{{ old('preferred_date') }}">
                            </div>
                        </div>
                    </div>

                    <div class="field">
                        <label for="contactMethod">طريقة التواصل المفضلة لدينا</label>
                        <div class="input-wrap">
                            <span class="svg-icon">
                                <svg viewBox="0 0 24 24">
                                    <path
                                        d="M22 16.9v3a2 2 0 0 1-2.2 2A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7l.4 2.8a2 2 0 0 1-.5 1.7L7.8 9.4a16 16 0 0 0 6.8 6.8l1.2-1.2a2 2 0 0 1 1.7-.5l2.8.4a2 2 0 0 1 1.7 2Z" />
                                </svg>
                            </span>
                            <select id="contactMethod" name="contact_method">
                                <option value="واتساب">محادثة واتساب سريعة</option>
                                <option value="اتصال هاتفي">اتصال هاتفي مباشر</option>
                                <option value="بريد إلكتروني">بريد إلكتروني رسمي</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- STEP 3: CONSULTATION DETAILS -->
                <div class="form-section" id="step3Section">
                    <h3 class="form-section-title">
                        <span class="svg-icon">
                            <svg viewBox="0 0 24 24">
                                <path d="M6 3h8l4 4v14H6Z" />
                                <path d="M14 3v5h5" />
                                <path d="M9 13h6" />
                                <path d="M9 17h6" />
                            </svg>
                        </span>
                        تفاصيل الطلب والمستندات
                    </h3>

                    <div class="field">
                        <label>درجة أولوية الطلب</label>
                        <div class="priority priority--pills">
                            <label class="priority-pill">
                                <input type="radio" name="priority" value="normal" checked>
                                عادية
                            </label>
                            <label class="priority-pill">
                                <input type="radio" name="priority" value="important">
                                مهمة
                            </label>
                            <label class="priority-pill">
                                <input type="radio" name="priority" value="urgent">
                                عاجلة جداً
                            </label>
                        </div>
                    </div>

                    <div class="field">
                        <label for="details">تفاصيل واستفسار القضية <span class="req">*</span></label>
                        <textarea id="details" name="details" required
                            placeholder="يرجى كتابة ملخص تفصيلي وواضح عن القضية أو النزاع أو الاستفسار المطلوب للحصول على أفضل تكييف قانوني...">{{ old('details') }}</textarea>
                    </div>

                    <div class="field">
                        <label>مرفقات ومستندات اختيارية</label>
                        <label class="upload-box" for="attachments">
                            <span class="svg-icon upload-icon">
                                <svg viewBox="0 0 24 24">
                                    <path d="M12 3v12" />
                                    <path d="m7 8 5-5 5 5" />
                                    <path d="M5 21h14" />
                                </svg>
                            </span>
                            <strong>اضغط لإرفاق مستندات داعمة</strong>
                            <span>PDF / Word / صور واضحة للقضية</span>
                            <input id="attachments" name="attachments[]" type="file" hidden multiple
                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                        </label>
                        <p class="upload-hint" id="uploadHint"></p>
                    </div>
                </div>

                <!-- Actual final submit button to be managed by Form Wizard script -->
                <button type="submit" class="btn btn-gold btn-submit-ticket">
                    <span class="svg-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M4 5h16v16H4Z" />
                            <path d="M8 3v4" />
                            <path d="M16 3v4" />
                            <path d="M4 10h16" />
                        </svg>
                    </span>
                    إرسال طلب الاستشارة
                </button>

                <p class="form-note">
                    <span class="svg-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 3 20 6v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6Z" />
                            <path d="m8 12 3 3 5-6" />
                        </svg>
                    </span>
                    جميع بياناتك مشفرة ومحمية بسرية تامة بموجب قانون تنظيم مهنة المحاماة.
                </p>
            </form>

            <!-- Sidebar workflow tracker -->
            <aside class="ticket-side">
                <h2>ماذا يحدث بعد إرسال الطلب؟</h2>
                <p class="muted">سيقوم طاقم المراجعة القانونية بتصنيف طلبك والتنسيق الفوري للتواصل معك.</p>

                <div class="steps">
                    <div class="step">
                        <b>1</b>
                        <div>
                            <strong>مراجعة ودراسة أولية</strong>
                            <span>ندرس استشارتك فور استلامها خلال ساعات العمل.</span>
                        </div>
                    </div>
                    <div class="step">
                        <b>2</b>
                        <div>
                            <strong>توجيه للمحامي المختص</strong>
                            <span>نقوم بإحالة ملفك لأكثر المحامين خبرة بمجال القضية.</span>
                        </div>
                    </div>
                    <div class="step">
                        <b>3</b>
                        <div>
                            <strong>تنسيق وتحديد الموعد</strong>
                            <span>نتصل بك هاتفياً أو عبر واتساب لتأكيد موعد المقابلة.</span>
                        </div>
                    </div>
                    <div class="step">
                        <b>4</b>
                        <div>
                            <strong>رعاية ومتابعة سرية</strong>
                            <span>تقديم تقرير قانوني متكامل وحفظ حقوقك بأقصى سرية.</span>
                        </div>
                    </div>
                </div>

                <!-- Trust Badges -->
                <div class="ticket-trust">
                    <div class="ticket-trust-item">
                        <span class="svg-icon">
                            <svg viewBox="0 0 24 24">
                                <path d="M12 3 20 6v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6Z" />
                                <path d="m8 12 3 3 5-6" />
                            </svg>
                        </span>
                        <span>سرية 100%</span>
                    </div>
                    <div class="ticket-trust-item">
                        <span class="svg-icon">
                            <svg viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="9" />
                                <path d="M12 7v6l4 2" />
                            </svg>
                        </span>
                        <span>تواصل فوري</span>
                    </div>
                    <div class="ticket-trust-item">
                        <span class="svg-icon">
                            <svg viewBox="0 0 24 24">
                                <circle cx="12" cy="8" r="5" />
                                <path d="m8.5 12.5-1.5 8 5-3 5 3-1.5-8" />
                            </svg>
                        </span>
                        <span>خبرة {{ $setting('stat_experience_value') }} سنة</span>
                    </div>
                </div>

                <!-- Quick Contact buttons -->
                <div class="ticket-contact-quick">
                    <p>هل تفضل التحدث المباشر مع مستشارنا؟</p>
                    <a class="btn btn-dark" href="{{ route('contact') }}">تواصل معنا الآن</a>
                    <a class="btn btn-outline-dark" href="https://wa.me/{{ $whatsappDigits }}" target="_blank"
                        rel="noopener">
                        واتساب مباشر
                    </a>
                </div>
            </aside>

        </div>
    </section>

</x-website.app-layout>
