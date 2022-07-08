@extends('layouts.app')

@section('title', $page->title)

@section('content')
    <h1>{{ $page->category->name }}</h1>

    <div class="row">
        <div class="col-md-3">
            <div class="list-group mb-3" role="tablist">
                @foreach($page->category->pages as $catPage)
                    <a href="{{ route('wiki.pages.show', [$page->category, $catPage]) }}" class="list-group-item @if($page->is($catPage)) active @endif">
                        {{ $catPage->title }}
                    </a>
                @endforeach
            </div>

            <a href="{{ route('wiki.index') }}" class="btn btn-secondary mb-3">
                <i class="bi bi-arrow-left"></i> {{ trans('wiki::messages.back') }}
            </a>
        </div>

        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    {!! $page->content !!}
                </div>
            </div>
        </div>
    </div>
@endsection
