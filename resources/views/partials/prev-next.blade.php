@if ($prevPage || $nextPage)
    <div class="row g-3 wiki-docs-prev-next mt-5">
        <div class="col-md-6">
            @if ($prevPage)
                <a href="{{ route('wiki.pages.show', [$prevPage->category, $prevPage]) }}" class="card wiki-docs-prev-next-card text-decoration-none">
                    <div class="card-body">
                        <small class="text-muted"><i class="bi bi-arrow-left"></i> {{ trans('wiki::messages.nav.previous') }}</small>
                        <div class="fw-semibold">{{ $prevPage->title }}</div>
                    </div>
                </a>
            @endif
        </div>
        <div class="col-md-6">
            @if ($nextPage)
                <a href="{{ route('wiki.pages.show', [$nextPage->category, $nextPage]) }}" class="card wiki-docs-prev-next-card text-decoration-none text-end">
                    <div class="card-body">
                        <small class="text-muted">{{ trans('wiki::messages.nav.next') }} <i class="bi bi-arrow-right"></i></small>
                        <div class="fw-semibold">{{ $nextPage->title }}</div>
                    </div>
                </a>
            @endif
        </div>
    </div>
@endif
