@extends('layouts.app')

@section('title', $page->title)

@section('content')
    <h1>{{ $page->category->name }}</h1>

    <div class="row" id="wiki">
        <div class="col-md-3">
            <div class="list-group mb-3" role="tablist">
                @foreach($page->category->pages as $catPage)
                    <a class="list-group-item @if($page->is($catPage)) active @endif" id="page-list-{{ $catPage->id }}" onclick="select({{ $catPage->id }}, '{{ $catPage->slug }}')">
                        {{ $catPage->title }}
                    </a>
                @endforeach
            </div>

            <a href="{{ route('wiki.index') }}" class="btn btn-secondary mb-3">
                <i class="bi bi-arrow-left"></i> {{ trans('wiki::messages.back') }}
            </a>
        </div>

        <div class="col-md-9">
            @foreach($page->category->pages as $catPage)
                <div class="card card-wiki" id="page-{{ $catPage->id }}" @if($catPage->id != $page->id) style="display: none;" @endif>
                    <div class="card-body">
                        {!! $catPage->content !!}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        var focusPage = {{ $page->id }};

        function select(pageId, catLink) {
            changeIfExist("page-" + focusPage, function (div) { div.style.display = 'none'; });
            changeIfExist("page-list-" + focusPage, function (div) { div.classList.remove("active"); });

            changeIfExist("page-" + pageId, function (div) { div.style.display = null; });
            changeIfExist("page-list-" + pageId, function (div) { div.classList.add("active"); });
            focusPage = pageId;
            window.history.pushState(null, null, catLink);
        }

        function changeIfExist(name, action) {
            let div = document.getElementById(name);
            if(div != null) {
                action(div);
            }
        }
    </script>
@endpush