<x-website.app-layout>
    <x-slot name="title">نتائج البحث - المكتبة القانونية</x-slot>

    <section class="inner-hero">
        <div class="container">
            <div class="breadcrumb"><a href="{{ route('legal-library.index') }}">المكتبة القانونية</a><span>/ نتائج البحث</span></div>
            <h1>نتائج البحث القانوني</h1>
            <p>نتائج البحث عن: {{ $term ?: 'كل عناصر المكتبة' }}</p>
        </div>
    </section>

    <section class="section section-soft">
        <div class="container">
            <form class="legal-search-box" method="GET" action="{{ route('legal-library.search') }}">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input name="q" value="{{ $term }}" placeholder="ابحث داخل المكتبة القانونية...">
                <button class="btn btn-gold" type="submit">بحث</button>
            </form>

            <div class="legal-list">
                @forelse ($results as $item)
                    <article class="legal-item-card">
                        <div>
                            <span class="legal-category">{{ $item->category_label }}</span>
                            <h2>{{ $item->title }}</h2>
                            <p>{{ $item->short_description ?: str($item->content)->limit(180) }}</p>
                            <time>{{ $item->published_at?->format('Y-m-d') ?: 'بدون تاريخ' }}</time>
                        </div>
                        <div class="legal-actions">
                            <a class="btn btn-gold" href="{{ route('legal-library.show', [$item->category_route, $item]) }}">عرض التفاصيل</a>
                            @if ($item->pdf_url)
                                <a class="btn btn-outline-dark" href="{{ $item->pdf_url }}" target="_blank" rel="noopener">تحميل PDF</a>
                            @endif
                        </div>
                    </article>
                @empty
                    <article class="legal-item-card">
                        <h2>لا توجد نتائج مطابقة</h2>
                        <p>جرّب البحث بكلمات أقل أو استعرض أقسام المكتبة القانونية.</p>
                    </article>
                @endforelse
            </div>

            {{ $results->links('vendor.pagination.website') }}
        </div>
    </section>
</x-website.app-layout>
