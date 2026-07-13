<li class="wiki-docs-tree-cat">
    <button class="wiki-docs-tree-cat-btn @if($category->expanded) expanded @endif"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#wiki-cat-{{ $category->id }}"
            aria-expanded="{{ $category->expanded ? 'true' : 'false' }}">
        <i class="bi bi-chevron-right wiki-docs-tree-chevron"></i>
        @if ($category->icon) <i class="{{ $category->icon }}"></i> @endif
        <span>{{ $category->name }}</span>
    </button>
    <ul id="wiki-cat-{{ $category->id }}" class="collapse wiki-docs-tree-children @if($category->expanded) show @endif list-unstyled">
        @foreach ($category->pages as $categoryPage)
            <li class="wiki-docs-tree-page @if($categoryPage->id === $activePageId) active @endif">
                <a href="{{ route('wiki.pages.show', [$category, $categoryPage]) }}">{{ $categoryPage->title }}</a>
            </li>
        @endforeach
        @foreach ($category->categories as $child)
            @include('wiki::partials.tree-node', ['category' => $child, 'activePageId' => $activePageId])
        @endforeach
    </ul>
</li>
