<ul class="wiki-docs-tree list-unstyled">
    @foreach ($tree as $rootCategory)
        @include('wiki::partials.tree-node', ['category' => $rootCategory, 'activePageId' => $activePageId])
    @endforeach
</ul>
