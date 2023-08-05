<?php

namespace Azuriom\Plugin\Wiki\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Plugin\Wiki\Models\Category;
use Azuriom\Plugin\Wiki\Models\Page;
use Azuriom\Plugin\Wiki\Requests\PageRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::parents()
            ->with(['categories', 'pages.category'])
            ->orderBy('position')
            ->get();

        return view('wiki::admin.pages.index', ['categories' => $categories]);
    }

    /**
     * Update the resources order in storage.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateOrder(Request $request)
    {
        $this->validate($request, [
            'categories' => ['required', 'array'],
        ]);

        $categories = $request->input('categories');

        $categoryPosition = 1;

        foreach ($categories as $category) {
            $id = $category['id'];
            $pages = $category['pages'] ?? [];
            $subCategories = $category['categories'] ?? [];

            Category::whereKey($id)->update([
                'position' => $categoryPosition++,
                'parent_id' => null,
            ]);

            $pagePosition = 1;

            foreach ($subCategories as $subCategory) {
                Category::whereKey($subCategory['id'])->update([
                    'position' => $pagePosition++,
                    'parent_id' => $id,
                ]);

                foreach ($subCategory['pages'] ?? [] as $page) {
                    Page::whereKey($page)->update([
                        'position' => $pagePosition++,
                        'category_id' => $subCategory['id'],
                    ]);
                }
            }

            foreach ($pages as $page) {
                Page::whereKey($page)->update([
                    'position' => $pagePosition++,
                    'category_id' => $id,
                ]);
            }
        }

        return response()->json([
            'message' => trans('wiki::admin.pages.updated'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('wiki::admin.pages.create', [
            'categories' => Category::all(),
            'pendingId' => old('pending_id', Str::uuid()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PageRequest $request)
    {
        $page = Page::create($request->validated());

        $page->persistPendingAttachments($request->input('pending_id'));

        return to_route('wiki.admin.pages.index')
            ->with('success', trans('messages.status.success'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page)
    {
        return view('wiki::admin.pages.edit', [
            'page' => $page,
            'categories' => Category::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PageRequest $request, Page $page)
    {
        $page->update($request->validated());

        return to_route('wiki.admin.pages.index')
            ->with('success', trans('messages.status.success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @throws \LogicException
     */
    public function destroy(Page $page)
    {
        $page->delete();

        return to_route('wiki.admin.pages.index')
            ->with('success', trans('messages.status.success'));
    }
}
