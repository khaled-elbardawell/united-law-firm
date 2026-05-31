<x-website.app-layout>
    @php
        $image = $course->hero_image ? (str_starts_with($course->hero_image, 'http') ? $course->hero_image : asset($course->hero_image)) : null;
    @endphp

    <x-slot name="title">{{ $course->title }} - {{ config('app.name') }}</x-slot>
    <x-slot name="head"><meta name="description" content="{{ $course->summary }}"></x-slot>

    <section class="training-show-hero">
        <div class="container training-show-grid">
            <div>
                <div class="breadcrumb">الرئيسية <span>/ الدورات التدريبية / {{ $course->title }}</span></div>
                <h1>{{ $course->title }}</h1>
                <p>{{ $course->summary }}</p>
                <div class="training-show-actions">
                    @if ($course->isRegistrationOpen())
                        <a class="btn btn-gold" href="#courseRegister">سجل الآن</a>
                    @endif
                    <a class="btn btn-outline" href="{{ route('training-courses.current') }}">الدورات الجديدة</a>
                </div>
            </div>
            <aside class="training-show-card">
                @if ($image)
                    <img src="{{ $image }}" alt="{{ $course->title }}">
                @else
                    <i class="fa-solid fa-chalkboard-user"></i>
                @endif
            </aside>
        </div>
    </section>

    <section class="section">
        <div class="container training-detail-layout">
            <main class="training-detail-main">
                <article class="training-panel">
                    <h2>تفاصيل الدورة</h2>
                    <p>{{ $course->description ?: $course->summary }}</p>
                </article>

                @if ($course->outcomes)
                    <article class="training-panel">
                        <h2>ماذا ستتعلم؟</h2>
                        <ul class="training-list">
                            @foreach ($course->outcomes as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    </article>
                @endif

                @if ($course->requirements)
                    <article class="training-panel">
                        <h2>متطلبات التسجيل</h2>
                        <ul class="training-list">
                            @foreach ($course->requirements as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    </article>
                @endif

                @if ($course->schedule_notes)
                    <article class="training-panel">
                        <h2>ملاحظات الجدول</h2>
                        <p>{{ $course->schedule_notes }}</p>
                    </article>
                @endif
            </main>

            <aside class="training-detail-side">
                <div class="training-info-box">
                    <h3>معلومات الدورة</h3>
                    @if ($course->trainer_name)<p><i class="fa-solid fa-user-tie"></i><strong>المدرب:</strong> {{ $course->trainer_name }}</p>@endif
                    @if ($course->location)<p><i class="fa-solid fa-location-dot"></i><strong>المكان:</strong> {{ $course->location }}</p>@endif
                    @if ($course->course_starts_at)<p><i class="fa-solid fa-calendar-day"></i><strong>البداية:</strong> {{ $course->course_starts_at->format('Y-m-d') }}</p>@endif
                    @if ($course->course_ends_at)<p><i class="fa-solid fa-calendar-check"></i><strong>النهاية:</strong> {{ $course->course_ends_at->format('Y-m-d') }}</p>@endif
                    <p><i class="fa-solid fa-clock"></i><strong>المدة:</strong> {{ $course->course_days }} أيام</p>
                    @if ($course->capacity)<p><i class="fa-solid fa-users"></i><strong>المقاعد:</strong> {{ $course->active_registrations_count }}/{{ $course->capacity }}</p>@endif
                    @if ($course->price)<p><i class="fa-solid fa-tag"></i><strong>الرسوم:</strong> {{ number_format((float) $course->price, 2) }}</p>@endif
                </div>

                <div class="training-register-box" id="courseRegister">
                    <h3>{{ $course->isRegistrationOpen() ? 'سجل الآن' : 'التسجيل مغلق' }}</h3>
                    @if (session('success'))
                        <div class="site-alert site-alert--success">{{ session('success') }}</div>
                    @endif
                    @if ($course->isRegistrationOpen())
                        <form method="POST" action="{{ route('training-courses.register', $course) }}">
                            @csrf
                            <input name="name" value="{{ old('name') }}" placeholder="الاسم الكامل" required>
                            <input name="phone" value="{{ old('phone') }}" placeholder="رقم الهاتف" required>
                            <input name="email" type="email" value="{{ old('email') }}" placeholder="البريد الإلكتروني">
                            <input name="profession" value="{{ old('profession') }}" placeholder="المهنة / جهة العمل">
                            <textarea name="notes" placeholder="ملاحظات إضافية">{{ old('notes') }}</textarea>
                            <button class="btn btn-gold" type="submit">إرسال التسجيل</button>
                        </form>
                    @else
                        <p class="muted">هذه الدورة غير متاحة للتسجيل حالياً.</p>
                    @endif
                </div>
            </aside>
        </div>
    </section>
</x-website.app-layout>
