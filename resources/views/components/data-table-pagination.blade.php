@if ($paginator->hasPages())
    <nav class="d-flex align-items-center gap-2">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="btn btn-sm btn-light disabled rounded-pill px-3 border-0 text-secondary opacity-50 small">Précédente</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="btn btn-sm btn-light rounded-pill px-3 border-0 text-secondary small">Précédente</a>
        @endif

        {{-- Pagination Elements --}}
        <div class="d-flex align-items-center gap-1">
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="px-2 text-secondary">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="btn btn-sm btn-primary rounded-circle d-flex align-items-center justify-content-center fw-bold p-0" style="width: 32px; height: 32px; font-size: 0.85rem;">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="btn btn-sm btn-white border-0 text-secondary small p-0 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-sm btn-light rounded-pill px-3 border-0 text-secondary small">Suivante</a>
        @else
            <span class="btn btn-sm btn-light disabled rounded-pill px-3 border-0 text-secondary opacity-50 small">Suivante</span>
        @endif
    </nav>
@endif
