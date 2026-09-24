<?php

namespace Azuriom\Plugin\Wiki\Controllers;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Plugin\Wiki\Models\Category;
use Azuriom\Plugin\Wiki\Models\Page;
use Azuriom\Plugin\Wiki\Support\PageNavigator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, PageNavigator $navigator)
    {
        if (setting('wiki.layout', 'default') === 'documentation') {
            $first = $navigator->firstPageOf(null, $request->user());

            if ($first === null) {
                return view('wiki::empty');
            }

            return to_route('wiki.pages.show', [$first->category, $first]);
        }

        $categories = Category::scopes(['parents', 'enabled'])
            ->get()
            ->filter(fn (Category $cat) => Gate::allows('view', $cat));

        return view('wiki::categories.index', ['categories' => $categories]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, PageNavigator $navigator, Category $category)
    {
        $this->authorize('view', $category);

        if (setting('wiki.layout', 'default') === 'documentation') {
            $first = $navigator->firstPageOf($category, $request->user());

            abort_if($first === null, 404);

            return to_route('wiki.pages.show', [$first->category, $first]);
        }

        $page = $category->pages->first();
        $subCategory = $category->categories()->scopes('enabled')->first();

        if ($page === null && $subCategory !== null) {
            $page = $subCategory->pages->first();
        }

        abort_if($page === null, 404);

        return to_route('wiki.pages.show', [$category, $page]);
    }

    /**
     * Find the resources for the specified query.
     */
    public function search(Request $request)
    {
        $search = $request->input('q');

        if ($request->ajax()) {
            $pages = $search === null ? collect() : Page::search($search)
                ->whereHas('category', fn (Builder $query) => $query->visibleTo($request->user()))
                ->with('category')
                ->limit(10)
                ->get();

            return view('wiki::partials.search-results', [
                'pages' => $pages,
                'search' => $search,
            ]);
        }

        if ($search === null) {
            return to_route('wiki.index');
        }

        $pages = Page::search($search)
            ->whereHas('category', fn (Builder $query) => $query->visibleTo($request->user()))
            ->with('category')
            ->paginate();

        return view('wiki::pages.search', [
            'pages' => $pages,
            'search' => $search,
        ]);
    }
}
