<?php

namespace Azuriom\Plugin\Wiki\Providers;

use Azuriom\Extensions\Plugin\BasePluginServiceProvider;
use Azuriom\Models\Permission;
use Azuriom\Plugin\Wiki\Models\Category;
use Azuriom\Plugin\Wiki\Models\Page;
use Azuriom\Plugin\Wiki\Policies\CategoryPolicy;
use Illuminate\Database\Eloquent\Relations\Relation;

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
        //
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
                'icon' => 'bi bi-book',
                'route' => 'wiki.admin.pages.index',
                'permission' => 'wiki.admin',
            ],
        ];
    }
}
