@extends('layouts.app')

@section('title', $page->title)

@section('content')
    <h1>{{ $page->category->name }}</h1>

    <div class="row" id="wiki">
        <div class="col-md-3">
            <div class="list-group mb-3" role="tablist">
                @foreach($page->category->pages as $catPage)
                    <a href="{{ route('wiki.pages.show', [$page->category, $catPage]) }}" class="list-group-item @if($page->is($catPage)) active @endif"
                       title="{{ $catPage->title }}"
                       onclick="selectWikiPage(this)"
                       data-bs-toggle="tab" role="tab"
                       data-bs-target="#page-{{ $catPage->id }}"
                       aria-controls="page-{{ $catPage->id }}" aria-selected="{{ $page->is($catPage) ? 'true' : 'false' }}">
                        {{ $catPage->title }}
                    </a>
                @endforeach
            </div>

            <a href="{{ route('wiki.index') }}" class="btn btn-secondary mb-3">
                <i class="bi bi-arrow-left"></i> {{ trans('wiki::messages.back') }}
            </a>
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
                history.replaceState({}, '', element.href);
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
