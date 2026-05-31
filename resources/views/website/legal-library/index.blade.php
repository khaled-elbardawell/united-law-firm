<x-website.app-layout>
    <x-slot name="title">المكتبة القانونية - {{ config('app.name') }}</x-slot>

    <section class="inner-hero">
        <div class="container">
            <div class="breadcrumb"><a href="{{ route('home') }}">الرئيسية</a><span>/ المكتبة القانونية</span></div>
            <h1>المكتبة القانونية</h1>
            <p>مكتبة تضم القوانين والقرارات القانونية والقرارات القضائية بصيغة مرتبة وسهلة البحث.</p>
        </div>
    </section>

    <section class="section section-soft">
        <div class="container">
            <form class="legal-search-box" method="GET" action="{{ route('legal-library.search') }}">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input name="q" value="{{ request('q') }}" placeholder="ابحث في القوانين والقرارات القضائية...">
                <button class="btn btn-gold" type="submit">بحث</button>
            </form>

            <div class="legal-library-grid">
                @foreach ($categories as $category)
                    <a class="legal-library-card" href="{{ $category['route'] }}">
                        <span class="legal-library-icon"><i class="{{ $category['icon'] }}"></i></span>
                        <strong>{{ $category['label'] }}</strong>
                        <small>{{ $category['count'] }} عنصر منشور</small>
                        <span class="legal-library-link">استعراض القسم <i class="fa-solid fa-arrow-left"></i></span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
</x-website.app-layout>
