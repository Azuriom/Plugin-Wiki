<div class="offcanvas offcanvas-end wiki-docs-search-panel" tabindex="-1" id="wikiSearchModal" aria-labelledby="wikiSearchModalLabel">
    <div class="offcanvas-header">
        <div class="wiki-docs-search-input-wrapper w-100">
            <i class="bi bi-search wiki-docs-search-icon"></i>
            <input type="text"
                   id="wikiSearchInput"
                   class="form-control wiki-docs-search-input"
                   placeholder="{{ trans('wiki::messages.search.placeholder') }}"
                   autocomplete="off">
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="{{ trans('messages.actions.close') }}"></button>
        </div>
    </div>
    <div class="offcanvas-body p-0">
        <div id="wikiSearchResults" class="wiki-docs-search-results"
             data-url="{{ route('wiki.search') }}">
            <div class="p-4 text-center text-muted">
                {{ trans('wiki::messages.search.type_to_search') }}
            </div>
        </div>
    </div>
</div>
