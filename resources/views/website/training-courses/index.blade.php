<x-website.app-layout>
    <x-slot name="title">{{ $title }} - {{ config('app.name') }}</x-slot>

    <section class="inner-hero">
        <div class="container">
            <div class="breadcrumb">الرئيسية <span>/ الدورات التدريبية</span></div>
            <h1>{{ $title }}</h1>
            <p>{{ $subtitle }}</p>
        </div>
    </section>

    <section class="section section-soft">
        <div class="container">
            <div class="training-grid">
                @forelse ($courses as $course)
                    @php
                        $image = $course->hero_image ? (str_starts_with($course->hero_image, 'http') ? $course->hero_image : asset($course->hero_image)) : null;
                    @endphp
                    <a class="training-card" href="{{ route('training-courses.show', $course) }}">
                        <div class="training-card-media">
                            @if ($image)
                                <img src="{{ $image }}" alt="{{ $course->title }}">
                            @else
                                <i class="fa-solid fa-chalkboard-user"></i>
                            @endif
                            @if ($course->isRegistrationOpen())
                                <span>التسجيل مفتوح</span>
                            @endif
                        </div>
                        <div class="training-card-body">
                            <h2>{{ $course->title }}</h2>
                            <p>{{ $course->summary }}</p>
                            <div class="training-meta">
                                @if ($course->course_starts_at)
                                    <span><i class="fa-solid fa-calendar-day"></i>{{ $course->course_starts_at->format('Y-m-d') }}</span>
                                @endif
                                <span><i class="fa-solid fa-clock"></i>{{ $course->course_days }} أيام</span>
                                @if ($course->capacity)
                                    <span><i class="fa-solid fa-users"></i>{{ $course->active_registrations_count }}/{{ $course->capacity }}</span>
                                @endif
                            </div>
                            <span class="training-link">عرض التفاصيل <i class="fa-solid fa-arrow-left"></i></span>
                        </div>
                    </a>
                @empty
                    <div class="training-empty">
                        <i class="fa-solid fa-calendar-xmark"></i>
                        <h2>{{ $emptyText }}</h2>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</x-website.app-layout>
