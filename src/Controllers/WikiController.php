<?php

namespace Azuriom\Plugin\Wiki\Controllers;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Plugin\Wiki\Models\Category;
use Illuminate\Support\Facades\Gate;

class WikiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::enabled()
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
        abort_if(! $category->is_enabled && ! Gate::allows('wiki.admin'), 403);

        return view('wiki::categories.show', ['category' => $category]);
    }
}
