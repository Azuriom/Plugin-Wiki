@extends('layouts.app')

@section('title', trans('wiki::messages.title'))

@section('content')
    <div class="container content">
        <h1>{{ trans('wiki::messages.title') }}</h1>

        <div class="row">
            @foreach($categories as $category)
                <div class="col-md-3">
                    <a href="{{ route('wiki.show', $category) }}">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="{{ $category->icon ?? 'fas fa-book' }} fa-fw fa-3x mb-3"></i>

                                <h2>{{ $category->name }}</h2>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endsection
