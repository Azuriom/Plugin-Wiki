<?php

namespace Azuriom\Plugin\Wiki\Providers;

use Azuriom\Extensions\Plugin\BasePluginServiceProvider;
use Azuriom\Models\Permission;
use Azuriom\Plugin\Wiki\Models\Page;
use Illuminate\Database\Eloquent\Relations\Relation;

class WikiServiceProvider extends BasePluginServiceProvider
{
    /**
     * Register any plugin services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any plugin services.
     *
     * @return void
     */
    public function boot()
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
     * @return array
     */
    protected function routeDescriptions()
    {
        return [
            'wiki.index' => 'wiki::messages.title',
        ];
    }

    /**
     * Return the admin navigations routes to register in the dashboard.
     *
     * @return array
     */
    protected function adminNavigation()
    {
        return [
            'wiki' => [
                'name' => 'wiki::admin.title',
                'icon' => 'fas fa-book',
                'route' => 'wiki.admin.pages.index',
                'permission' => 'wiki.admin',
            ],
        ];
    }
}
