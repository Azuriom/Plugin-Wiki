@extends('layouts.app')

@section('title', trans('wiki::messages.title'))

@section('content')
    <div class="container py-5">
        <div class="alert alert-info text-center">
            {{ trans('wiki::messages.empty') }}
        </div>
    </div>
@endsection
