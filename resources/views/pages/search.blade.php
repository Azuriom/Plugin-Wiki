@extends('layouts.app')

@section('title', trans('wiki::messages.search.results'))

@section('content')
    @include('wiki::partials._header', ['title' => trans('wiki::messages.search.results')])

    <div id="wiki">
        @forelse($pages as $page)
            @can('view', $page->category)
                <div class="card mb-3">
                    <div class="card-body">
                        <h2 class="card-title mb-2">
                            <a href="{{ route('wiki.pages.show', [$page->category, $page]) }}">
                                {{ $page->title }}
                            </a>
                        </h2>

                        <span class="badge bg-primary fs-6 mb-3">
                            <i class="{{ $page->category->icon ?? 'bi bi-book' }}"></i>
                            {{ $page->category->name }}
                        </span>

                        <p class="card-text">{{ Str::limit(strip_tags($page->content), 300) }}</p>
                    </div>
                </div>
            @endcan

            {{ $pages->withQueryString()->links() }}
        @empty
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> {{ trans('wiki::messages.search.empty') }}
            </div>
        @endforelse
    </div>
@endsection
