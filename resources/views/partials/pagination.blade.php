@if ($paginator->hasPages())
@php
    $current = $paginator->currentPage();
    $last = $paginator->lastPage();
    $start = max(1, $current - 2);
    $end = min($last, $current + 2);

    $baseClass = 'px-4 py-2 rounded-xl text-sm font-semibold border transition-colors';
    $idle = 'border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:border-blue-500 hover:text-blue-600 dark:hover:text-blue-400';
    $active = 'bg-blue-600 border-blue-600 text-white shadow';
    $disabled = 'border-slate-200 dark:border-slate-800 text-slate-300 dark:text-slate-700 cursor-not-allowed';
@endphp
<nav aria-label="Pagination" class="mt-12 flex flex-wrap items-center justify-center gap-2">
    @if ($paginator->onFirstPage())
        <span class="{{ $baseClass }} {{ $disabled }}">{{ __('site.blog_prev') }}</span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="{{ $baseClass }} {{ $idle }}">{{ __('site.blog_prev') }}</a>
    @endif

    @if ($start > 1)
        <a href="{{ $paginator->url(1) }}" class="{{ $baseClass }} {{ $idle }}">1</a>
        @if ($start > 2)
            <span class="px-2 text-slate-400">…</span>
        @endif
    @endif

    @for ($page = $start; $page <= $end; $page++)
        @if ($page === $current)
            <span class="{{ $baseClass }} {{ $active }}" aria-current="page">{{ $page }}</span>
        @else
            <a href="{{ $paginator->url($page) }}" class="{{ $baseClass }} {{ $idle }}">{{ $page }}</a>
        @endif
    @endfor

    @if ($end < $last)
        @if ($end < $last - 1)
            <span class="px-2 text-slate-400">…</span>
        @endif
        <a href="{{ $paginator->url($last) }}" class="{{ $baseClass }} {{ $idle }}">{{ $last }}</a>
    @endif

    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="{{ $baseClass }} {{ $idle }}">{{ __('site.blog_next') }}</a>
    @else
        <span class="{{ $baseClass }} {{ $disabled }}">{{ __('site.blog_next') }}</span>
    @endif
</nav>
@endif
