@extends('admin.layouts.admin')

@section('title', trans('admin.pages.title'))

@if(Auth::user()->isAdmin())
    @push('footer-scripts')
        <script src="{{ asset('vendor/sortablejs/Sortable.min.js') }}"></script>
        <script>
            const sortable = Sortable.create(document.getElementById('categories'), {
                animation: 150,
                group: 'categories',
                handle: '.sortable-handle',
            });

            document.querySelectorAll('.wiki-list').forEach(function (el) {
                Sortable.create(el, {
                    group: {
                        name: 'pages',
                    },
                    animation: 150,
                    handle: '.sortable-handle',
                });
            });

            function serialize(categories) {
                return [].slice.call(categories).map(function (category) {
                    const pages = category.querySelector('.wiki-list');

                    const pagesId = [].slice.call(pages.children).map(function (categoryPackage) {
                        return categoryPackage.dataset['id'];
                    });

                    return {
                        id: category.dataset['categoryId'],
                        pages: pagesId,
                    };
                });
            }

            const saveButton = document.getElementById('save');
            const saveButtonIcon = saveButton.querySelector('.btn-spinner');

            saveButton.addEventListener('click', function () {
                saveButton.setAttribute('disabled', '');
                saveButtonIcon.classList.remove('d-none');

                axios.post('{{ route('wiki.admin.pages.update-order') }}', {
                    'categories': serialize(sortable.el.children),
                }).then(function (json) {
                    createAlert('success', json.data.message, true);
                }).catch(function (error) {
                    createAlert('danger', error.response.data.message ? error.response.data.message : error, true)
                }).finally(function () {
                    saveButton.removeAttribute('disabled');
                    saveButtonIcon.classList.add('d-none');
                });
            });
        </script>
    @endpush
@endif

@section('content')
    <div class="card shadow mb-4">
        <div class="card-body">

            @if($categories->isEmpty())
                <div class="alert alert-info" role="alert">
                    <i class="fas fa-info-circle"></i>
                    {{ trans('wiki::admin.categories.empty') }}
                </div>
            @endif

            <ol class="list-unstyled sortable mb-3" id="categories">
                @foreach($categories as $category)
                    <li class="sortable-item sortable-dropdown mb-5" data-category-id="{{ $category->id }}">
                        <div class="card">
                            <div class="card-body d-flex justify-content-between">
                                <span>
                                    <i class="fas fa-arrows-alt sortable-handle"></i> {{ $category->name }}
                                </span>
                                <span>
                                    <a href="{{ route('wiki.admin.categories.edit', $category) }}" class="mx-1" title="{{ trans('messages.actions.edit') }}" data-toggle="tooltip"><i class="fas fa-edit"></i></a>
                                    <a href="{{ route('wiki.admin.categories.destroy', $category) }}" class="mx-1" title="{{ trans('messages.actions.delete') }}" data-toggle="tooltip" data-confirm="delete"><i class="fas fa-trash"></i></a>
                                </span>
                            </div>
                        </div>

                        <ol class="list-unstyled sortable sortable-list wiki-list">
                            @foreach($category->pages as $page)
                                <li class="sortable-item sortable-dropdown" data-id="{{ $page->id }}">
                                    <div class="card">
                                        <div class="card-body d-flex justify-content-between">
                                                <span>
                                                    <i class="fas fa-arrows-alt sortable-handle"></i>

                                                    <span>{{ $page->title }}</span>
                                                </span>

                                            <span>
                                                <a href="{{ route('wiki.admin.pages.edit', $page) }}" class="m-1" title="{{ trans('messages.actions.edit') }}" data-toggle="tooltip"><i class="fas fa-edit"></i></a>
                                                <a href="{{ route('wiki.admin.pages.destroy', $page) }}" class="m-1" title="{{ trans('messages.actions.delete') }}" data-toggle="tooltip" data-confirm="delete"><i class="fas fa-trash"></i></a>
                                            </span>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                    </li>
                @endforeach
            </ol>

            <button type="button" class="btn btn-success" id="save">
                <i class="fas fa-save"></i> {{ trans('messages.actions.save') }}
                <span class="spinner-border spinner-border-sm btn-spinner d-none" role="status"></span>
            </button>

            <a href="{{ route('wiki.admin.categories.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> {{ trans('wiki::admin.categories.create') }}
            </a>

            @if(! $categories->isEmpty())
                <a class="btn btn-primary" href="{{ route('wiki.admin.pages.create') }}">
                    <i class="fas fa-plus"></i> {{ trans('messages.actions.add') }}
                </a>
            @endif
        </div>
    </div>
@endsection
