<?php

namespace Azuriom\Plugin\Wiki\View\Composers;

use Azuriom\Plugin\Wiki\Models\Category;
use Azuriom\Plugin\Wiki\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class TreeComposer
{
    public function __construct(private readonly Request $request)
    {
    }

    public function compose(View $view): void
    {
        $user = $this->request->user();
        $activePageId = $this->resolveActivePageId();

        $tree = Category::visibleTo($user)
            ->whereNull('parent_id')
            ->orderBy('position')
            ->with(['pages' => fn ($q) => $q->orderBy('position')])
            ->with(['categories' => fn ($q) => $q->visibleTo($user)->orderBy('position')
                ->with(['pages' => fn ($q2) => $q2->orderBy('position')])
                ->with(['categories' => fn ($q2) => $q2->visibleTo($user)->orderBy('position')
                    ->with(['pages' => fn ($q3) => $q3->orderBy('position')]), ]), ])
            ->get();

        $this->annotateDescendants($tree, $activePageId, true);

        $view->with([
            'tree' => $tree,
            'activePageId' => $activePageId,
        ]);
    }

    private function annotateDescendants(Collection $categories, ?int $activePageId, bool $isRoot): void
    {
        foreach ($categories as $category) {
            $ids = collect($category->pages->pluck('id'));
            $children = $category->categories ?? collect();

            if ($children->isNotEmpty()) {
                $this->annotateDescendants($children, $activePageId, false);

                foreach ($children as $child) {
                    $ids = $ids->merge($child->descendantPageIds);
                }
            }

            $category->setAttribute('descendantPageIds', $ids);
            $category->setAttribute('expanded', $isRoot || $ids->contains($activePageId));
        }
    }

    private function resolveActivePageId(): ?int
    {
        $page = $this->request->route('page');

        return $page instanceof Page ? $page->id : null;
    }
}
