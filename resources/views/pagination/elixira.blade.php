@if ($paginator->hasPages())
    <nav class="elx-pagination" role="navigation" aria-label="Pagination">
        <ul class="elx-pagination__list">
            @if ($paginator->onFirstPage())
                <li class="elx-pagination__item disabled"><span>&lsaquo;</span></li>
            @else
                <li class="elx-pagination__item"><a href="{{ $paginator->previousPageUrl() }}" rel="prev">&lsaquo;</a></li>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="elx-pagination__item disabled"><span>{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="elx-pagination__item active"><span>{{ $page }}</span></li>
                        @else
                            <li class="elx-pagination__item"><a href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li class="elx-pagination__item"><a href="{{ $paginator->nextPageUrl() }}" rel="next">&rsaquo;</a></li>
            @else
                <li class="elx-pagination__item disabled"><span>&rsaquo;</span></li>
            @endif
        </ul>
    </nav>
@endif

<style>
    .elx-pagination { display: flex; justify-content: center; margin: 2rem 0; }
    .elx-pagination__list { display: flex; gap: 0.4rem; list-style: none; padding: 0; margin: 0; flex-wrap: wrap; }
    .elx-pagination__item a,
    .elx-pagination__item span {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 38px; height: 38px; padding: 0 0.65rem;
        border-radius: 999px; border: 1px solid rgba(74, 200, 246, 0.45);
        color: #4ac8f6; text-decoration: none; font-weight: 700;
        background: rgba(74, 200, 246, 0.1);
        transition: background 0.2s ease, color 0.2s ease, border-color 0.2s ease;
    }
    .elx-pagination__item.active span {
        background: #4ac8f6; color: #0b161c; border-color: #4ac8f6;
        box-shadow: 0 4px 14px rgba(74, 200, 246, 0.35);
    }
    .elx-pagination__item.disabled span { opacity: 0.35; }
    .elx-pagination__item a:hover {
        background: #4ac8f6; color: #0b161c; border-color: #4ac8f6;
    }
</style>
