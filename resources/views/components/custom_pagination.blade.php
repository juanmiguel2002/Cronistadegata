{{-- resources/views/vendor/pagination/custom.blade.php --}}
<div class="pagination-info">
    Página {{ $paginator->currentPage() }} de {{ $paginator->lastPage() }}
</div>
<div class="pagination">
    @if ($paginator->hasPages())
        <ul class="pagination">
            {{-- Botón "Anterior" --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span class="page-link" aria-hidden="true">&laquo; Anterior</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&laquo; Anterior</a>
                </li>
            @endif

            {{-- Números de página --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Botón "Siguiente" --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">Siguiente &raquo;</a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span class="page-link" aria-hidden="true">Siguiente &raquo;</span>
                </li>
            @endif
        </ul>
        {{-- Paginación simplificada (solo móvil) --}}
        <ul class="pagination-simple">
            {{-- Botón "Anterior" --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}">&laquo;</a></li>
            @endif

            {{-- Página 1 --}}
            <li class="page-item {{ $paginator->currentPage() == 1 ? 'active' : '' }}">
                <a class="page-link" href="{{ $paginator->url(1) }}">1</a>
            </li>

            {{-- "..." si hace falta --}}
            @if ($paginator->currentPage() > 3)
                <li class="page-item disabled"><span class="page-link">…</span></li>
            @endif

            {{-- Página actual (si no es primera ni última) --}}
            @if ($paginator->currentPage() > 1 && $paginator->currentPage() < $paginator->lastPage())
                <li class="page-item active"><span class="page-link">{{ $paginator->currentPage() }}</span></li>
            @endif

            {{-- "..." si hace falta --}}
            @if ($paginator->currentPage() < $paginator->lastPage() - 2)
                <li class="page-item disabled"><span class="page-link">…</span></li>
            @endif

            {{-- Última página --}}
            @if ($paginator->lastPage() > 1)
                <li class="page-item {{ $paginator->currentPage() == $paginator->lastPage() ? 'active' : '' }}">
                    <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a>
                </li>
            @endif

            {{-- Botón "Siguiente" --}}
            @if ($paginator->hasMorePages())
                <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}">&raquo;</a></li>
            @else
                <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
            @endif
        </ul>
    @endif
</div>
