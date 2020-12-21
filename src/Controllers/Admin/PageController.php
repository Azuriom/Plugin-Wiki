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
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('wiki::admin.pages.index', [
            'categories' => Category::with('pages')->get(),
        ]);
    }

    /**
     * Update the resources order in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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

            Category::whereKey($id)->update([
                'position' => $categoryPosition++,
            ]);

            $pagePosition = 1;

            foreach ($pages as $page) {
                Page::whereKey($page)->update([
                    'position' => $pagePosition++,
                    'category_id' => $id,
                ]);
            }
        }

        return response()->json([
            'message' => trans('wiki::admin.pages.order-updated'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
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
     *
     * @param  \Azuriom\Plugin\Wiki\Requests\PageRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PageRequest $request)
    {
        $page = Page::create($request->validated());

        $page->persistPendingAttachments($request->input('pending_id'));

        return redirect()->route('wiki.admin.pages.index')
            ->with('success', trans('admin.pages.status.created'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Azuriom\Plugin\Wiki\Models\Page  $page
     * @return \Illuminate\Http\Response
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
     *
     * @param  \Azuriom\Plugin\Wiki\Requests\PageRequest  $request
     * @param  \Azuriom\Plugin\Wiki\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function update(PageRequest $request, Page $page)
    {
        $page->update($request->validated());

        return redirect()->route('wiki.admin.pages.index')
            ->with('success', trans('admin.pages.status.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Azuriom\Plugin\Wiki\Models\Page  $page
     * @return \Illuminate\Http\Response
     *
     * @throws \Exception
     */
    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()->route('wiki.admin.pages.index')
            ->with('success', trans('admin.pages.status.deleted'));
    }
}
