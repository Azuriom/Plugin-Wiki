<?php

namespace Azuriom\Plugin\Wiki\View\Composers;

use Azuriom\Plugin\Wiki\Models\Page;
use Azuriom\Plugin\Wiki\Support\PageNavigator;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NavigatorComposer
{
    public function __construct(
        private readonly Request $request,
        private readonly PageNavigator $navigator,
    ) {
    }

    public function compose(View $view): void
    {
        $page = $this->request->route('page');
        $user = $this->request->user();

        $view->with([
            'prevPage' => $page instanceof Page ? $this->navigator->previous($page, $user) : null,
            'nextPage' => $page instanceof Page ? $this->navigator->next($page, $user) : null,
        ]);
    }
}
