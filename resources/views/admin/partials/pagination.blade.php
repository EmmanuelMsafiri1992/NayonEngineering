@if ($paginator->hasPages())
    <div class="admin-pagination">
        <div class="pagination-info">
            Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} entries
        </div>
        <ul class="pagination-list">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link"><i class="fas fa-chevron-left"></i></span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev"><i class="fas fa-chevron-left"></i></a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next"><i class="fas fa-chevron-right"></i></a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link"><i class="fas fa-chevron-right"></i></span>
                </li>
            @endif
        </ul>
    </div>

    <style>
        .admin-pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
            flex-wrap: wrap;
            gap: 15px;
        }
        .pagination-info {
            color: var(--text-muted);
            font-size: 14px;
        }
        .pagination-list {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 5px;
        }
        .page-item .page-link {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 12px;
            border: 1px solid var(--border);
            border-radius: 6px;
            background: var(--white);
            color: var(--text);
            text-decoration: none;
            font-size: 14px;
            transition: all 0.2s;
        }
        .page-item .page-link:hover {
            background: var(--light);
            border-color: var(--primary);
            color: var(--primary);
        }
        .page-item.active .page-link {
            background: var(--primary);
            border-color: var(--primary);
            color: var(--white);
        }
        .page-item.disabled .page-link {
            background: var(--light);
            color: var(--text-muted);
            cursor: not-allowed;
        }
        .page-item.disabled .page-link:hover {
            border-color: var(--border);
        }
    </style>
@endif
