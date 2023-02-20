<?php

namespace Azuriom\Plugin\Wiki\Controllers;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Plugin\Wiki\Models\Category;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
     *
     * @param  \Azuriom\Plugin\Wiki\Models\Category  $category
     * @return \Illuminate\Http\Response
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

        return redirect()->route('wiki.pages.show', [$category, $page]);
    }
}
