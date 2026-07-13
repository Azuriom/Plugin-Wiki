@extends('wiki::layouts.documentation')

@section('title', $page->title)

@section('wiki-content')
    <article class="wiki-docs-article">
        <header class="wiki-docs-article-header mb-4">
            <h1 class="wiki-docs-article-title">{{ $page->title }}</h1>
        </header>

        <div class="wiki-docs-article-body">
            {!! $page->content !!}
        </div>

        @include('wiki::partials.prev-next')
    </article>
@endsection
