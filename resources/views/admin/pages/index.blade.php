@extends('admin.layouts.admin')

@section('title', trans('admin.pages.title'))

@push('footer-scripts')
    <script src="{{ asset('vendor/sortablejs/Sortable.min.js') }}"></script>
    <script>
        const sortable = Sortable.create(document.getElementById('categories'), {
            animation: 150,
            group: {
                name: 'pages',
                put: function (to, sortable, drag) {
                    return drag.classList.contains('category-parent');
                },
            },
            handle: '.sortable-handle',
        });

        document.querySelectorAll('.wiki-list').forEach(function (el) {
            Sortable.create(el, {
                group: {
                    name: 'pages',
                    put: function (to, sortable, drag) {
                        if (!drag.classList.contains('category-parent')) {
                            return true;
                        }
                        return !drag.querySelector('.category-parent .category-parent')
                            && drag.parentNode.id === 'categories';
                    },
                },
                animation: 150,
                handle: '.sortable-handle',
            });
        });

        function serializeCategory(category, preventNested = false) {
            const pagesId = [];
            const subCategories = [];
            const pages = category.querySelector('.wiki-list');

            [].slice.call(pages.children).forEach(function (pageCategory) {
                if (!pageCategory.classList.contains('category-parent')) {
                    pagesId.push(pageCategory.dataset['id']);
                    return;
                }

                if (!preventNested) {
                    subCategories.push(serializeCategory(pageCategory, true));
                }
            });

            return {
                id: category.dataset['categoryId'],
                categories: subCategories,
                pages: pagesId
            };
        }

        function serialize(categories) {
            return [].slice.call(categories.children).map(function (category) {
                return serializeCategory(category);
            });
        }

        const saveButton = document.getElementById('save');
        const saveButtonIcon = saveButton.querySelector('.btn-spinner');

        saveButton.addEventListener('click', function () {
            saveButton.setAttribute('disabled', '');
            saveButtonIcon.classList.remove('d-none');

            axios.post('{{ route('wiki.admin.pages.update-order') }}', {
                'categories': serialize(sortable.el),
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

@section('content')
    <div class="card shadow mb-4">
        <div class="card-body">

            @if($categories->isEmpty())
                <div class="alert alert-info" role="alert">
                    <i class="bi bi-info-circle"></i>
                    {{ trans('wiki::admin.categories.empty') }}
                </div>
            @endif

            <ol class="list-unstyled sortable mb-3" id="categories">
                @each('wiki::admin.pages._category', $categories, 'category')
            </ol>

            <button type="button" class="btn btn-success" id="save">
                <i class="bi bi-save"></i> {{ trans('messages.actions.save') }}
                <span class="spinner-border spinner-border-sm btn-spinner d-none" role="status"></span>
            </button>

            <a href="{{ route('wiki.admin.categories.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> {{ trans('wiki::admin.categories.add') }}
            </a>

            @if(! $categories->isEmpty())
                <a class="btn btn-primary" href="{{ route('wiki.admin.pages.create') }}">
                    <i class="bi bi-plus-lg"></i> {{ trans('messages.actions.add') }}
                </a>
            @endif
        </div>
    </div>

    <div class="alert alert-info" role="alert">
        <i class="bi bi-info-circle"></i> @lang('wiki::admin.categories.info')
    </div>
@endsection
