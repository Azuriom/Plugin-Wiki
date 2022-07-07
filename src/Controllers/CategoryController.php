<?php

namespace Azuriom\Plugin\Wiki\Controllers;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Plugin\Wiki\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::enabled()
            ->has('pages')
            ->with('pages')
            ->orderBy('position')
            ->get();

        return view('wiki::categories.index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Azuriom\Plugin\Wiki\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        $page = $category->pages->first();

        abort_if($page === null, 404);

        return redirect()->route('wiki.pages.show', [$category, $page]);
    }
}
