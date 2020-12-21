@extends('admin.layouts.admin')

@section('title', trans('admin.pages.title-edit', ['page' => $page->id]))

@section('content')
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('wiki.admin.pages.update', $page) }}" method="POST">
                @method('PUT')

                @include('wiki::admin.pages._form')

                @include('admin.elements.editor', ['imagesUploadUrl' => route('wiki.admin.pages.attachments.store', $page)])

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> {{ trans('messages.actions.save') }}
                </button>

                <a href="{{ route('wiki.admin.pages.destroy', $page) }}" class="btn btn-danger" data-confirm="delete">
                    <i class="fas fa-trash"></i> {{ trans('messages.actions.delete') }}
                </a>
            </form>
        </div>
    </div>
@endsection
