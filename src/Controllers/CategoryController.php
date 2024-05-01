<?php

namespace Azuriom\Plugin\Wiki\Controllers;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Plugin\Wiki\Models\Category;
use Azuriom\Plugin\Wiki\Models\Page;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::scopes(['parents', 'enabled'])
            ->get()
            ->filter(fn (Category $cat) => Gate::allows('view', $cat));

        return view('wiki::categories.index', ['categories' => $categories]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $this->authorize('view', $category);

        $page = $category->pages->first();
        $subCategory = $category->categories->first();

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

        if ($search === null) {
            return to_route('wiki.index');
        }

        $pages = Page::search($search)
            ->whereHas('category', fn (Builder $query) => $query->enabled())
            ->with('category')
            ->paginate();

        return view('wiki::pages.search', [
            'pages' => $pages,
            'search' => $search,
        ]);
    }
}
