<?php

namespace Azuriom\Plugin\Wiki\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Models\Role;
use Azuriom\Plugin\Wiki\Models\Category;
use Azuriom\Plugin\Wiki\Requests\CategoryRequest;

class CategoryController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('wiki::admin.categories.create', [
            'categories' => Category::parents()->get(),
            'roles' => Role::orderByDesc('power')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        Category::create($request->validated());

        return to_route('wiki.admin.pages.index')
            ->with('success', trans('messages.status.success'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('wiki::admin.categories.edit', [
            'category' => $category,
            'categories' => Category::parents()->get()->except($category->id),
            'roles' => Role::orderByDesc('power')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        return to_route('wiki.admin.pages.index')
            ->with('success', trans('messages.status.success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @throws \LogicException
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return to_route('wiki.admin.pages.index')
            ->with('success', trans('messages.status.success'));
    }
}
