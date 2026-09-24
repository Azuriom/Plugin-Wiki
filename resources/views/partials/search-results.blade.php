@if ($pages->isEmpty())
    <div class="p-4 text-center text-muted">
        {{ trans('wiki::messages.search.no_results') }}
    </div>
@else
    <div class="list-group list-group-flush">
        @foreach ($pages->groupBy('category.name') as $category => $categoryPages)
            <div class="list-group-item bg-transparent text-uppercase small fw-semibold text-muted py-2">
                {{ $category }}
            </div>

            @foreach ($categoryPages as $page)
                <a href="{{ route('wiki.pages.show', [$page->category, $page]) }}"
                   class="list-group-item list-group-item-action wiki-docs-search-result">
                    <div class="fw-semibold">{{ $page->title }}</div>
                    <div class="small text-muted mt-1">{!! $page->searchExcerpt($search) !!}</div>
                </a>
            @endforeach
        @endforeach
    </div>
@endif
