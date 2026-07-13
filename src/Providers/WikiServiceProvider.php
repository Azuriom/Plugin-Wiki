<?php

namespace Azuriom\Plugin\Wiki\Providers;

use Azuriom\Extensions\Plugin\BasePluginServiceProvider;
use Azuriom\Models\Permission;
use Azuriom\Plugin\Wiki\Models\Category;
use Azuriom\Plugin\Wiki\Models\Page;
use Azuriom\Plugin\Wiki\Policies\CategoryPolicy;
use Azuriom\Plugin\Wiki\Support\PageNavigator;
use Azuriom\Plugin\Wiki\View\Composers\NavigatorComposer;
use Azuriom\Plugin\Wiki\View\Composers\TreeComposer;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\View;

class WikiServiceProvider extends BasePluginServiceProvider
{
    /**
     * The policy mappings for this plugin.
     *
     * @var array<string, string>
     */
    protected array $policies = [
        Category::class => CategoryPolicy::class,
    ];

    /**
     * Register any plugin services.
     */
    public function register(): void
    {
        $this->app->singleton(PageNavigator::class);
    }

    /**
     * Bootstrap any plugin services.
     */
    public function boot(): void
    {
        $this->loadViews();

        $this->loadTranslations();

        $this->loadMigrations();

        $this->registerRouteDescriptions();

        $this->registerAdminNavigation();

        $this->registerUserNavigation();

        Permission::registerPermissions([
            'wiki.admin' => 'wiki::admin.permission',
        ]);

        Relation::morphMap(['wiki.pages' => Page::class]);

        View::composer('wiki::partials.tree', TreeComposer::class);
        View::composer('wiki::partials.prev-next', NavigatorComposer::class);
    }

    /**
     * Returns the routes that should be able to be added to the navbar.
     *
     * @return array<string, string>
     */
    protected function routeDescriptions(): array
    {
        return [
            'wiki.index' => trans('wiki::messages.title'),
        ];
    }

    /**
     * Return the admin navigations routes to register in the dashboard.
     *
     * @return array<string, array<string, string>>
     */
    protected function adminNavigation(): array
    {
        return [
            'wiki' => [
                'name' => trans('wiki::admin.title'),
                'type' => 'dropdown',
                'icon' => 'bi bi-book',
                'route' => 'wiki.admin.*',
                'permission' => 'wiki.admin',
                'items' => [
                    'wiki.admin.pages.index' => trans('wiki::admin.pages.title'),
                    'wiki.admin.settings' => trans('wiki::admin.settings.title'),
                ],
            ],
        ];
    }
}
