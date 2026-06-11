@php
    $featuredPost = $posts->first();
    $remainingPosts = $posts->getCollection()->slice(1);
@endphp

<x-website.app-layout>
    <x-slot name="title">المدونة - {{ \App\Models\SiteSetting::getValue('site_name', config('app.name')) }}</x-slot>

    <section class="inner-hero" id="blogHero">
        <div class="container">
            <div class="breadcrumb">الرئيسية <span>/ المدونة</span></div>
            <h1>المدونة القانونية</h1>
            <p>مقالات عملية ورؤى قانونية مختصرة تساعدك على فهم حقوقك وخياراتك قبل اتخاذ القرار.</p>
        </div>
    </section>

    <section class="section section-soft" id="blogList">
        <div class="container">
            <div class="blog-toolbar">
                <div>
                    <span class="kicker">آخر المقالات</span>
                    <h2>اقرأ، افهم، ثم اختر خطوتك القانونية بثقة</h2>
                    <p>استخدم الفلاتر للوصول السريع للمقال المناسب، أو ابدأ من المقالات الأحدث أدناه.</p>
                </div>
                <div class="blog-count">
                    <strong>{{ number_format($posts->total()) }}</strong>
                    <span>مقال منشور</span>
                </div>
            </div>

            <form method="GET" class="blog-filter-panel">
                <div class="blog-search-field">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input name="q" value="{{ request('q') }}" placeholder="ابحث في عنوان أو محتوى المقال...">
                </div>
                <select name="category">
                    <option value="">كل التصنيفات</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->name }}</option>
                    @endforeach
                </select>
                <select name="tag">
                    <option value="">كل الوسوم</option>
                    @foreach ($tags as $tag)
                        <option value="{{ $tag->slug }}" @selected(request('tag') === $tag->slug)>{{ $tag->name }}</option>
                    @endforeach
                </select>
                <button class="btn btn-gold" type="submit">تطبيق</button>
                <a class="btn btn-outline-dark" href="{{ route('blog.index') }}">مسح</a>
            </form>

            @if ($categories->isNotEmpty())
                <div class="blog-category-strip">
                    <a class="{{ request('category') ? '' : 'active' }}" href="{{ route('blog.index', request()->except('category', 'page')) }}">الكل</a>
                    @foreach ($categories as $category)
                        <a class="{{ request('category') === $category->slug ? 'active' : '' }}" href="{{ route('blog.index', array_merge(request()->except('page'), ['category' => $category->slug])) }}">{{ $category->name }}</a>
                    @endforeach
                </div>
            @endif

            @if ($featuredPost)
                <article class="blog-featured-card">
                    <a class="blog-featured-media" href="{{ route('blog.show', $featuredPost->slug) }}">
                        @if ($featuredPost->featured_image)
                            <img src="{{ str_starts_with($featuredPost->featured_image, 'http') ? $featuredPost->featured_image : asset($featuredPost->featured_image) }}" alt="{{ $featuredPost->title }}">
                        @else
                            <span><i class="fa-solid fa-scale-balanced"></i></span>
                        @endif
                    </a>
                    <div class="blog-featured-body">
                        <div class="blog-meta">
                            <span><i class="fa-solid fa-folder"></i> {{ $featuredPost->category?->name ?: 'عام' }}</span>
                            <span><i class="fa-solid fa-calendar-days"></i> {{ $featuredPost->published_at?->format('Y-m-d') }}</span>
                            <span><i class="fa-solid fa-eye"></i> {{ number_format($featuredPost->views_count) }}</span>
                        </div>
                        <h2><a href="{{ route('blog.show', $featuredPost->slug) }}">{{ $featuredPost->title }}</a></h2>
                        <p>{{ $featuredPost->excerpt ?: str($featuredPost->content)->stripTags()->limit(220) }}</p>
                        <div class="blog-card-footer">
                            <div class="blog-author">
                                <i class="fa-solid fa-user-pen"></i>
                                <span>{{ $featuredPost->display_author_name }}</span>
                            </div>
                            <a class="blog-read-link" href="{{ route('blog.show', $featuredPost->slug) }}">قراءة المقال <i class="fa-solid fa-arrow-left"></i></a>
                        </div>
                    </div>
                </article>
            @endif

            <div class="blog-posts-grid blog-posts-grid--polished">
                @forelse ($remainingPosts as $post)
                    <article class="blog-card">
                        <a class="blog-card-media" href="{{ route('blog.show', $post->slug) }}">
                            @if ($post->featured_image)
                                <img src="{{ str_starts_with($post->featured_image, 'http') ? $post->featured_image : asset($post->featured_image) }}" alt="{{ $post->title }}">
                            @else
                                <span><i class="fa-solid fa-scale-balanced"></i></span>
                            @endif
                        </a>
                        <div class="blog-card-body">
                            <div class="blog-meta">
                                <span>{{ $post->category?->name ?: 'عام' }}</span>
                                <span>{{ $post->published_at?->format('Y-m-d') }}</span>
                            </div>
                            <h2><a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a></h2>
                            <p>{{ $post->excerpt ?: str($post->content)->stripTags()->limit(145) }}</p>
                            <div class="blog-card-footer">
                                <div class="blog-author">
                                    <i class="fa-solid fa-user-pen"></i>
                                    <span>{{ $post->display_author_name }}</span>
                                </div>
                                <a class="blog-read-link" href="{{ route('blog.show', $post->slug) }}"><i class="fa-solid fa-arrow-left"></i></a>
                            </div>
                        </div>
                    </article>
                @empty
                    @unless ($featuredPost)
                        <div class="blog-empty">
                            <h2>لا توجد مقالات منشورة حاليا</h2>
                            <p>ستظهر المقالات هنا بعد نشرها من لوحة التحكم.</p>
                        </div>
                    @endunless
                @endforelse

                <div class="blog-pagination">
                    {{ $posts->links('vendor.pagination.website') }}
                </div>
            </div>
        </div>
    </section>
</x-website.app-layout>
