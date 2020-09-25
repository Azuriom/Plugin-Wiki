@extends('layouts.app')

@section('title', $category->name)

@section('content')
    <div class="container content">
        <h1>{{ $category->name }}</h1>

        <a href="{{ route('wiki.index') }}" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> {{ trans('wiki::messages.back') }}
        </a>

        <div class="row">
            <div class="col-md-3">
                <div class="list-group" role="tablist">
                    @foreach($category->pages as $count => $page)
                        <a href="#page{{ $page->id }}" class="list-group-item @if($count === 0) active @endif" data-toggle="tab" role="tab" aria-controls="page{{ $page->id }}" aria-selected="{{ $count === 0 ? 'true' : 'false' }}">
                            {{ $page->title }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="col-md-9 tab-content">
                @foreach($category->pages as $count => $page)
                    <div class="tab-pane fade @if($count === 0) show active @endif" id="page{{ $page->id }}" role="tabpanel" aria-labelledby="nav-home-tab">
                        <h2>{{ $page->title }}</h2>

                        {!! $page->content !!}
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
