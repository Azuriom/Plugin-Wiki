@extends('admin.layouts.admin')

@section('title', trans('wiki::admin.settings.title'))

@section('content')
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('wiki.admin.settings.save') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label" for="layoutSelect">{{ trans('wiki::admin.settings.layout') }}</label>
                    <select class="form-select" id="layoutSelect" name="layout">
                        <option value="default" @selected($layout === 'default')>{{ trans('wiki::admin.settings.layout_default') }}</option>
                        <option value="documentation" @selected($layout === 'documentation')>{{ trans('wiki::admin.settings.layout_documentation') }}</option>
                    </select>
                    <div class="form-text">{{ trans('wiki::admin.settings.layout_info') }}</div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> {{ trans('messages.actions.save') }}
                </button>
            </form>
        </div>
    </div>
@endsection
