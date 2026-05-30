@if ($paginator->hasPages())
    <nav class="admin-pagination" role="navigation" aria-label="Pagination Navigation">
        <div class="admin-pagination__summary">
            عرض
            <strong>{{ $paginator->firstItem() }}</strong>
            إلى
            <strong>{{ $paginator->lastItem() }}</strong>
            من
            <strong>{{ $paginator->total() }}</strong>
        </div>

        <div class="admin-pagination__controls">
            @if ($paginator->onFirstPage())
                <span class="admin-page-link is-disabled" aria-disabled="true">السابق</span>
            @else
                <a class="admin-page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">السابق</a>
            @endif

            <div class="admin-pagination__pages">
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="admin-page-link is-disabled">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="admin-page-link is-active" aria-current="page">{{ $page }}</span>
                            @else
                                <a class="admin-page-link" href="{{ $url }}">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            @if ($paginator->hasMorePages())
                <a class="admin-page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">التالي</a>
            @else
                <span class="admin-page-link is-disabled" aria-disabled="true">التالي</span>
            @endif
        </div>
    </nav>
@endif
