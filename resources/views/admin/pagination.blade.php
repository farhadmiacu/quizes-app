@if ($paginator->hasPages())
<nav style="display:flex;align-items:center;justify-content:space-between;gap:12px;padding:4px 0">
    <div style="font-size:0.78rem;color:var(--muted)">
        Showing {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} of {{ $paginator->total() }}
    </div>
    <div style="display:flex;align-items:center;gap:4px">

        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span class="pg-btn disabled">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="pg-btn">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
            </a>
        @endif

        {{-- Pages --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="pg-btn disabled">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="pg-btn active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="pg-btn">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="pg-btn">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
        @else
            <span class="pg-btn disabled">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
            </span>
        @endif

    </div>
</nav>

<style>
.pg-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    height: 32px;
    padding: 0 8px;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 500;
    color: var(--muted);
    background: var(--surface2);
    border: 1px solid var(--border);
    text-decoration: none;
    transition: all 0.15s;
}
.pg-btn:not(.disabled):not(.active):hover {
    color: var(--text);
    border-color: var(--accent);
}
.pg-btn.active {
    background: var(--accent);
    color: #000;
    border-color: var(--accent);
    font-weight: 700;
}
.pg-btn.disabled {
    opacity: 0.35;
    cursor: default;
}
</style>
@endif
