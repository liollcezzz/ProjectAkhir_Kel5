@if ($paginator->hasPages())
<nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="bg-white border border-ink-200/70 rounded-3xl p-4 shadow-sm">
  <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <p class="text-sm text-ink-600">
      {!! __('Showing') !!}
      @if ($paginator->firstItem())
        <span class="font-semibold text-brand">{{ $paginator->firstItem() }}</span>
        {!! __('to') !!}
        <span class="font-semibold text-brand">{{ $paginator->lastItem() }}</span>
      @else
        {{ $paginator->count() }}
      @endif
      {!! __('of') !!}
      <span class="font-semibold text-brand">{{ $paginator->total() }}</span>
      {!! __('results') !!}
    </p>

    <div class="flex flex-wrap items-center justify-center gap-3">
      @if ($paginator->onFirstPage())
        <span class="inline-flex items-center justify-center h-10 px-4 text-sm font-medium text-ink-500 bg-ink-50 border border-ink-200 rounded-full cursor-default">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
          {!! __('pagination.previous') !!}
        </span>
      @else
        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center h-10 px-4 text-sm font-medium text-brand border border-ink-200 rounded-full bg-white hover:border-brand-accent hover:text-brand-accent transition">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
          {!! __('pagination.previous') !!}
        </a>
      @endif

      <span class="inline-flex items-center justify-center h-10 min-w-[110px] px-4 text-sm font-semibold text-brand bg-white border border-ink-200 rounded-full">
        {{ __('Page') }} {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
      </span>

      @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center h-10 px-4 text-sm font-medium text-brand border border-ink-200 rounded-full bg-white hover:border-brand-accent hover:text-brand-accent transition">
          {!! __('pagination.next') !!}
          <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </a>
      @else
        <span class="inline-flex items-center justify-center h-10 px-4 text-sm font-medium text-ink-500 bg-ink-50 border border-ink-200 rounded-full cursor-default">
          {!! __('pagination.next') !!}
          <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </span>
      @endif
    </div>
  </div>
</nav>
@endif
