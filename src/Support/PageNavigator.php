<?php

namespace Azuriom\Plugin\Wiki\Support;

use Azuriom\Models\User;
use Azuriom\Plugin\Wiki\Models\Category;
use Azuriom\Plugin\Wiki\Models\Page;
use Illuminate\Support\Collection;

class PageNavigator
{
    private ?Collection $cachedSequence = null;

    private ?int $cachedUserKey = null;

    /**
     * Get all the pages visible to the given user, ordered by their position
     * in the categories tree.
     */
    public function sequence(?User $user): Collection
    {
        $userKey = $user?->id ?? 0;

        if ($this->cachedSequence !== null && $this->cachedUserKey === $userKey) {
            return $this->cachedSequence;
        }

        $rootCategories = Category::visibleTo($user)
            ->parents()
            ->get();

        $sequence = collect();

        foreach ($rootCategories as $root) {
            $this->appendCategoryPages($root, $user, $sequence);
        }

        $this->cachedSequence = $sequence;
        $this->cachedUserKey = $userKey;

        return $sequence;
    }

    public function firstPageOf(?Category $category, ?User $user): ?Page
    {
        if ($category === null) {
            return $this->sequence($user)->first();
        }

        $sequence = collect();

        $this->appendCategoryPages($category, $user, $sequence);

        return $sequence->first();
    }

    public function next(Page $page, ?User $user): ?Page
    {
        $sequence = $this->sequence($user);
        $index = $sequence->search(fn (Page $p) => $p->id === $page->id);

        if ($index === false || $index === $sequence->count() - 1) {
            return null;
        }

        return $sequence->get($index + 1);
    }

    public function previous(Page $page, ?User $user): ?Page
    {
        $sequence = $this->sequence($user);
        $index = $sequence->search(fn (Page $p) => $p->id === $page->id);

        if ($index === false || $index === 0) {
            return null;
        }

        return $sequence->get($index - 1);
    }

    private function appendCategoryPages(Category $category, ?User $user, Collection $sink): void
    {
        foreach ($category->pages()->orderBy('position')->get() as $page) {
            $sink->push($page);
        }

        $children = Category::visibleTo($user)
            ->where('parent_id', $category->id)
            ->orderBy('position')
            ->get();

        foreach ($children as $child) {
            $this->appendCategoryPages($child, $user, $sink);
        }
    }
}
