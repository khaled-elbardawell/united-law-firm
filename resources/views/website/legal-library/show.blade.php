<x-website.app-layout>
    <x-slot name="title">{{ $item->seo_title ?: $item->title }} - المكتبة القانونية</x-slot>
    @if ($item->seo_description)
        <x-slot name="head">
            <meta name="description" content="{{ $item->seo_description }}">
        </x-slot>
    @endif

    <section class="inner-hero">
        <div class="container">
            <div class="breadcrumb">
                <a href="{{ route('home') }}">الرئيسية</a>
                <span>/</span>
                <a href="{{ route('legal-library.index') }}">المكتبة القانونية</a>
                <span>/ {{ $item->category_label }}</span>
            </div>
            <h1>{{ $item->title }}</h1>
            <p>{{ $item->short_description }}</p>
        </div>
    </section>

    <section class="section section-soft">
        <div class="container legal-detail-grid">
            <form class="legal-search-box" method="GET" action="{{ route('legal-library.search') }}">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input name="q" value="{{ request('q') }}" placeholder="ابحث داخل المكتبة القانونية...">
                <button class="btn btn-gold" type="submit">بحث</button>
            </form>

            <article class="legal-detail-card">
                <div class="legal-meta">
                    <span>{{ $item->category_label }}</span>
                    <time>{{ $item->published_at?->format('Y-m-d') ?: 'بدون تاريخ' }}</time>
                </div>
                <div class="legal-detail-content">
                    {!! nl2br(e($item->content ?: $item->short_description)) !!}
                </div>

                @if ($item->pdf_url)
                    <div class="legal-pdf-viewer">
                        <div class="legal-pdf-head">
                            <strong>ملف PDF المرفق</strong>
                            <a class="btn btn-gold" href="{{ $item->pdf_url }}" target="_blank" rel="noopener">تحميل الملف</a>
                        </div>
                        <iframe src="{{ $item->pdf_url }}" title="{{ $item->title }}"></iframe>
                    </div>
                @endif
            </article>
        </div>
    </section>
</x-website.app-layout>
