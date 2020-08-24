@extends('admin.layouts.admin')

@section('title', trans('wiki::admin.categories.title-edit', ['category' => $category->id]))

@section('content')
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('wiki.admin.categories.update', $category) }}" method="POST">
                @method('PUT')

                @include('wiki::admin.categories._form')

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> {{ trans('messages.actions.save') }}
                </button>

                <a href="{{ route('wiki.admin.categories.destroy', $category) }}" class="btn btn-danger" data-confirm="delete">
                    <i class="fas fa-trash"></i> {{ trans('messages.actions.delete') }}
                </a>
            </form>
        </div>
    </div>
@endsection
