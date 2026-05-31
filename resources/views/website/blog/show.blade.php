@php
    $postUrl = route('blog.show', $post->slug);
    $shareTitle = $post->title;
@endphp

<x-website.app-layout>
    <x-slot name="title">{{ $post->meta_title ?: $post->title }} - {{ \App\Models\SiteSetting::getValue('site_name', config('app.name')) }}</x-slot>
    <x-slot name="head">
        <meta name="description" content="{{ $post->meta_description ?: ($post->excerpt ?: str($post->content)->stripTags()->limit(160)) }}">
        <meta name="keywords" content="{{ $post->meta_keywords }}">
        <meta name="author" content="{{ $post->author?->name ?: \App\Models\SiteSetting::getValue('seo_author', config('app.name')) }}">
        <meta name="robots" content="{{ $post->is_indexable ? 'index,follow' : 'noindex,nofollow' }}">
        <link rel="canonical" href="{{ $postUrl }}">
        <meta property="og:title" content="{{ $post->meta_title ?: $post->title }}">
        <meta property="og:description" content="{{ $post->meta_description ?: $post->excerpt }}">
        @php $ogImage = $post->og_image ?: $post->featured_image; @endphp
        @if ($ogImage)
            <meta property="og:image" content="{{ str_starts_with($ogImage, 'http') ? $ogImage : asset($ogImage) }}">
        @endif
    </x-slot>

    <section class="inner-hero blog-article-hero" id="blogPostHero">
        <div class="container">
            <div class="breadcrumb">
                <a href="{{ route('home') }}">الرئيسية</a>
                <span>/</span>
                <a href="{{ route('blog.index') }}">المدونة</a>
                @if ($post->category)<span>/ {{ $post->category->name }}</span>@endif
            </div>
            <h1>{{ $post->title }}</h1>
            @if ($post->excerpt)
                <p>{{ $post->excerpt }}</p>
            @endif
        </div>
    </section>

    <section class="section section-soft" id="blogPost">
        <div class="container blog-post-layout">
            <article class="blog-post-article">
                <header class="blog-article-head">
                    <a class="blog-back-link" href="{{ route('blog.index') }}">
                        <i class="fa-solid fa-arrow-right"></i>
                        العودة للمدونة
                    </a>

                    <div class="blog-post-info">
                        <span><i class="fa-solid fa-user-pen"></i> {{ $post->author?->name ?: 'إدارة الموقع' }}</span>
                        <span><i class="fa-solid fa-calendar-days"></i> {{ $post->published_at?->format('Y-m-d') }}</span>
                        <span><i class="fa-solid fa-eye"></i> {{ number_format($post->views_count) }} قراءة</span>
                        @if ($post->category)<span><i class="fa-solid fa-folder"></i> {{ $post->category->name }}</span>@endif
                    </div>
                </header>

                @if ($post->featured_image)
                    <figure class="blog-post-cover">
                        <img src="{{ str_starts_with($post->featured_image, 'http') ? $post->featured_image : asset($post->featured_image) }}" alt="{{ $post->title }}">
                    </figure>
                @endif

                <div class="blog-share">
                    <span>شارك المقال</span>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($postUrl) }}" target="_blank" rel="noopener" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode($postUrl) }}&text={{ urlencode($shareTitle) }}" target="_blank" rel="noopener" aria-label="X"><i class="fa-brands fa-x-twitter"></i></a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($postUrl) }}" target="_blank" rel="noopener" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                    <a href="https://wa.me/?text={{ urlencode($shareTitle.' '.$postUrl) }}" target="_blank" rel="noopener" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
                </div>

                <div class="blog-content">
                    {!! $post->content !!}
                </div>

                @if ($post->tags->isNotEmpty())
                    <div class="blog-tags">
                        @foreach ($post->tags as $tag)
                            <a href="{{ route('blog.index', ['tag' => $tag->slug]) }}">{{ $tag->name }}</a>
                        @endforeach
                    </div>
                @endif
            </article>

            @if ($relatedPosts->isNotEmpty())
                <section class="blog-related blog-related--wide">
                    <div class="blog-related-head">
                        <span class="kicker">اقرأ أيضا</span>
                        <h3>مقالات ذات صلة</h3>
                    </div>
                    <div class="blog-related-grid">
                        @foreach ($relatedPosts as $related)
                            <a href="{{ route('blog.show', $related->slug) }}">
                                <strong>{{ $related->title }}</strong>
                                <span>{{ $related->published_at?->format('Y-m-d') }}</span>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    </section>
</x-website.app-layout>
