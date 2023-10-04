@extends('layouts.app')

@section('title', trans('wiki::messages.title'))

@section('content')
    @include('wiki::partials._header', ['title' => trans('wiki::messages.title')])

    <div class="row" id="wiki">
        @foreach($categories as $category)
            <div class="col-md-3">
                <a href="{{ route('wiki.show', $category) }}">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="{{ $category->icon ?? 'bi bi-book' }} fs-1 mb-3 text-primary"></i>

                            <h2>{{ $category->name }}</h2>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
@endsection
