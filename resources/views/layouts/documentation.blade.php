@extends('layouts.app')

@push('styles')
    <link href="{{ plugin_asset('wiki', 'css/documentation.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="wiki-docs-shell container-fluid px-0">
        <div class="d-lg-none d-flex gap-2 mb-3 px-3">
            <button class="btn btn-outline-secondary" type="button"
                    data-bs-toggle="offcanvas" data-bs-target="#wikiTreeOffcanvas"
                    aria-label="{{ trans('messages.nav.toggle') }}">
                <i class="bi bi-list"></i>
            </button>
            <button type="button" class="btn btn-outline-secondary flex-grow-1 wiki-docs-search-trigger text-start"
                    data-bs-toggle="offcanvas" data-bs-target="#wikiSearchModal">
                <i class="bi bi-search me-2"></i>
                <span>{{ trans('wiki::messages.search.placeholder') }}</span>
            </button>
        </div>

        <div class="row g-4 mx-0">
            <aside class="offcanvas-lg offcanvas-start col-lg-3 ps-0 wiki-docs-tree-aside"
                   tabindex="-1" id="wikiTreeOffcanvas">
                <div class="offcanvas-header d-lg-none">
                    <h5 class="offcanvas-title">{{ trans('wiki::messages.title') }}</h5>
                    <button type="button" class="btn-close"
                            data-bs-dismiss="offcanvas" data-bs-target="#wikiTreeOffcanvas"
                            aria-label="{{ trans('messages.actions.close') }}"></button>
                </div>
                <div class="offcanvas-body wiki-docs-sidebar" id="wiki-tree">
                    @include('wiki::partials.tree')
                </div>
            </aside>

            <main class="col-lg-6 col-md-8" id="wiki-content" data-index-url="{{ route('wiki.index') }}">
                @yield('wiki-content')
            </main>

            <aside class="col-lg-3 d-none d-lg-block pe-0">
                <div class="wiki-docs-sidebar sticky-top">
                    <button type="button"
                            class="btn btn-outline-secondary w-100 mb-3 wiki-docs-search-trigger wiki-docs-search-trigger--sidebar"
                            data-bs-toggle="offcanvas" data-bs-target="#wikiSearchModal">
                        <i class="bi bi-search"></i>
                        <span class="wiki-docs-search-trigger-label">{{ trans('wiki::messages.search.placeholder') }}</span>
                        <kbd class="wiki-docs-kbd">⌘K</kbd>
                    </button>
                    @include('wiki::partials.toc')
                </div>
            </aside>
        </div>
    </div>

    @include('wiki::partials.search-modal')
@endsection

@push('footer-scripts')
    <script src="{{ plugin_asset('wiki', 'js/documentation.js') }}" defer></script>
@endpush
