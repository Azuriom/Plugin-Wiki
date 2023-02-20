<li class="sortable-item sortable-dropdown category-parent" data-category-id="{{ $category->id }}">
    <div class="card">
        <div class="card-body d-flex justify-content-between">
            <span>
                <i class="bi bi-arrows-move sortable-handle"></i>
                {{ $category->name }}
            </span>
            <span>
                <a href="{{ route('wiki.admin.categories.edit', $category) }}" class="mx-1" title="{{ trans('messages.actions.edit') }}" data-bs-toggle="tooltip"><i class="bi bi-pencil-square"></i></a>
                <a href="{{ route('wiki.admin.categories.destroy', $category) }}" class="mx-1" title="{{ trans('messages.actions.delete') }}" data-bs-toggle="tooltip" data-confirm="delete"><i class="bi bi-trash"></i></a>
            </span>
        </div>
    </div>

    <ol class="list-unstyled sortable sortable-list wiki-list">
        @each('wiki::admin.pages._category', $category->categories, 'category')

        @foreach($category->pages as $page)
            <li class="sortable-item sortable-dropdown" data-id="{{ $page->id }}">
                <div class="card">
                    <div class="card-body d-flex justify-content-between">
                        <span>
                            <i class="bi bi-arrows-move sortable-handle"></i>
                            <a href="{{ route('wiki.pages.show', [$page->category, $page]) }}" target="_blank">
                                {{ $page->title }}
                            </a>
                        </span>
                        <span>
                            <a href="{{ route('wiki.admin.pages.edit', $page) }}" class="m-1" title="{{ trans('messages.actions.edit') }}" data-bs-toggle="tooltip"><i class="bi bi-pencil-square"></i></a>
                            <a href="{{ route('wiki.admin.pages.destroy', $page) }}" class="m-1" title="{{ trans('messages.actions.delete') }}" data-bs-toggle="tooltip" data-confirm="delete"><i class="bi bi-trash"></i></a>
                        </span>
                    </div>
                </div>
            </li>
        @endforeach
    </ol>
</li>
