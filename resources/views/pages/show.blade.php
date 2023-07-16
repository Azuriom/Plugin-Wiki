@extends('layouts.app')

@section('title', $page->title)

@section('content')
    @include('wiki::partials._header', ['title' => $page->category->name])

    <div class="row" id="wiki">
        <div class="col-md-3">
            @if(! $page->category->categories->isEmpty())
                <div class="list-group mb-3" role="tablist">
                    @foreach($page->category->categories as $subCategory)
                        @can('view', $subCategory)
                            <a href="{{ route('wiki.show', [$subCategory]) }}" class="list-group-item">
                                <i class="{{ $category->icon ?? 'bi bi-book' }}"></i> {{ $subCategory->name }}
                            </a>
                        @endcan
                    @endforeach
                </div>
            @endif

            <div class="list-group mb-3" role="tablist">
                @foreach($page->category->pages as $catPage)
                    <a href="{{ route('wiki.pages.show', [$page->category, $catPage]) }}" class="list-group-item @if($page->is($catPage)) active @endif"
                       title="{{ $catPage->title }}"
                       onclick="selectWikiPage(this)"
                       data-bs-toggle="tab" data-bs-target="#page-{{ $catPage->id }}" role="tab"
                       aria-controls="page-{{ $catPage->id }}" aria-selected="{{ $page->is($catPage) ? 'true' : 'false' }}">
                        {{ $catPage->title }}
                    </a>
                @endforeach
            </div>

            @if($page->category->parent !== null)
                <a href="{{ route('wiki.show', $page->category->parent) }}" class="btn btn-secondary mb-3">
                    <i class="bi bi-arrow-left"></i> {{ trans('wiki::messages.back') }}
                </a>
            @else
                <a href="{{ route('wiki.index') }}" class="btn btn-secondary mb-3">
                    <i class="bi bi-arrow-left"></i> {{ trans('wiki::messages.back') }}
                </a>
            @endif
        </div>

        <div class="col-md-9 tab-content">
            @foreach($page->category->pages as $catPage)
                <div class="tab-pane fade @if($page->is($catPage)) show active @endif" id="page-{{ $catPage->id }}" role="tabpanel" aria-labelledby="nav-home-tab">
                    <div class="card">
                        <div class="card-body">
                            {!! $catPage->content !!}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let currentTitle = '{{ $page->title }}';

        function selectWikiPage(element, replaceState = false) {
            const tab = bootstrap.Tab.getOrCreateInstance(element);
            tab.show();

            if (replaceState) {
                window.history.replaceState({}, '', element.href);
            } else {
                window.history.pushState({}, '', element.href);
            }

            document.title = document.title.replace(currentTitle, element.title);
            currentTitle = element.title;
        }

        window.onpopstate = function(e) {
            const target = document.querySelector('[href="' + e.target.location.href + '"]');

            if (target) {
                selectWikiPage(target, true);
            }
        };
    </script>
@endpush
