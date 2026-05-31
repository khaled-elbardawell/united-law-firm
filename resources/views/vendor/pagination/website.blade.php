@if ($paginator->hasPages())
    <nav class="site-pagination" role="navigation" aria-label="Pagination Navigation">
        <div class="site-pagination__summary">
            عرض {{ $paginator->firstItem() }} - {{ $paginator->lastItem() }} من {{ $paginator->total() }} مقال
        </div>

        <div class="site-pagination__controls">
            @if ($paginator->onFirstPage())
                <span class="site-page-link is-disabled" aria-disabled="true">
                    <i class="fa-solid fa-angle-right"></i>
                </span>
            @else
                <a class="site-page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="السابق">
                    <i class="fa-solid fa-angle-right"></i>
                </a>
            @endif

            <div class="site-pagination__pages">
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="site-page-link is-disabled">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="site-page-link is-active" aria-current="page">{{ $page }}</span>
                            @else
                                <a class="site-page-link" href="{{ $url }}">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            @if ($paginator->hasMorePages())
                <a class="site-page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="التالي">
                    <i class="fa-solid fa-angle-left"></i>
                </a>
            @else
                <span class="site-page-link is-disabled" aria-disabled="true">
                    <i class="fa-solid fa-angle-left"></i>
                </span>
            @endif
        </div>
    </nav>
@endif
