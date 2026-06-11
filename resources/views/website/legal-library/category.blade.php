<x-website.app-layout>
    <x-slot name="title">{{ $categoryLabel }} - المكتبة القانونية</x-slot>

    <section class="inner-hero">
        <div class="container">
            <div class="breadcrumb">
                <a href="{{ route('home') }}">الرئيسية</a>
                <span>/</span>
                <a href="{{ route('legal-library.index') }}">المكتبة القانونية</a>
                <span>/ {{ $categoryLabel }}</span>
            </div>
            <h1>{{ $categoryLabel }}</h1>
            <p>استعرض أحدث العناصر المنشورة ضمن هذا القسم مع إمكانية تحميل ملفات PDF المتاحة.</p>
        </div>
    </section>

    <section class="section section-soft">
        <div class="container">
            <form class="legal-search-box legal-search-box--with-reset" method="GET" action="{{ route('legal-library.category', $categorySlug) }}">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input name="q" value="{{ $term }}" placeholder="ابحث داخل {{ $categoryLabel }}...">
                <button class="btn btn-gold" type="submit">بحث</button>
                @if ($term)
                    <a class="btn btn-outline-dark" href="{{ route('legal-library.category', $categorySlug) }}">مسح</a>
                @endif
            </form>

            <div class="legal-list">
                @forelse ($items as $item)
                    <article class="legal-item-card">
                        <div>
                            <span class="legal-category">{{ $item->category_label }}</span>
                            <h2>{{ $item->title }}</h2>
                            <p>{{ $item->short_description ?: str($item->content)->limit(180) }}</p>
                            <time>{{ $item->published_at?->format('Y-m-d') ?: 'بدون تاريخ' }}</time>
                        </div>
                        <div class="legal-actions">
                            <a class="btn btn-gold" href="{{ route('legal-library.show', [$categorySlug, $item]) }}">عرض التفاصيل</a>
                            @if ($item->pdf_url)
                                <a class="btn btn-outline-dark" href="{{ $item->pdf_url }}" target="_blank" rel="noopener">تحميل PDF</a>
                            @endif
                        </div>
                    </article>
                @empty
                    <article class="legal-item-card">
                        <h2>لا توجد عناصر منشورة حالياً</h2>
                        <p>سيتم عرض العناصر هنا بعد إضافتها وتفعيلها من لوحة التحكم.</p>
                    </article>
                @endforelse
            </div>

            {{ $items->links('vendor.pagination.website') }}
        </div>
    </section>
</x-website.app-layout>
