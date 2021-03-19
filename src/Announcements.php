<?php

namespace Tino\Announcements;

use Event;
use Route;
use Tino\Announcements\Events\EmailNotificationRequested;
use Tino\Announcements\Hooks\NavbarItemsHook;
use Tino\Announcements\Hooks\ScriptsHook;
use Tino\Announcements\Hooks\StylesHook;
use Tino\Announcements\Listeners\SendEmailNotification;
use Tino\Announcements\Repositories\AnnouncementsRepository;
use Tino\Announcements\Repositories\EloquentAnnouncements;
use Tino\Plugins\Plugin;
use Tino\Support\Sidebar\Item;
use Tino\Announcements\Listeners\ActivityLogSubscriber;
use Tino\Plugins\Tino;

class Announcements extends Plugin
{
    /**
     * A sidebar item for the plugin.
     * @return Item|null
     */
    public function sidebar()
    {
        return Item::create(__('Announcements'))
            ->icon('fas fa-bullhorn')
            ->route('announcements.index')
            ->permissions('announcements.manage')
            ->active('announcements*');
    }

    /**
     * Register plugin services.
     */
    public function register()
    {
        $this->app->singleton(AnnouncementsRepository::class, EloquentAnnouncements::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function boot()
    {
        $this->registerViews();
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'announcements');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'announcements');
        $this->loadJsonTranslationsFrom(__DIR__.'/../resources/lang');

        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations')
        ], 'migrations');

        $this->mapRoutes();

        $this->registerHooks();

        $this->registerEventListeners();

        $this->publishAssets();
    }

    /**
     * Register plugin views.
     *
     * @return void
     */
    protected function registerViews()
    {
        $viewsPath = __DIR__.'/../resources/views';

        $this->publishes([
            $viewsPath => resource_path('views/vendor/plugins/announcements')
        ], 'views');

        $this->loadViewsFrom($viewsPath, 'announcements');
    }

    /**
     * Map all plugin related routes.
     */
    protected function mapRoutes()
    {
        $this->mapWebRoutes();

        if ($this->app['config']->get('auth.expose_api')) {
            $this->mapApiRoutes();
        }
    }

    /**
     * Map web plugin related routes.
     */
    protected function mapWebRoutes()
    {
        Route::group([
            'namespace' => 'Tino\Announcements\Http\Controllers\Web',
            'middleware' => 'web',
        ], function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        });
    }

    /**
     * Map API plugin related routes.
     */
    protected function mapApiRoutes()
    {
        Route::group([
            'namespace' => 'Tino\Announcements\Http\Controllers\Api',
            'middleware' => 'api',
            'prefix' => 'api',
        ], function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        });
    }

    /**
     * Register plugin event listeners.
     */
    private function registerEventListeners()
    {
        // Register activity log subscriber only if
        // UserActivity plugin is installed.
        if ($this->app->bound('Tino\UserActivity\Repositories\Activity\ActivityRepository')) {
            Event::subscribe(ActivityLogSubscriber::class);
        }

        Event::listen(EmailNotificationRequested::class, SendEmailNotification::class);
    }

    /**
     * Register all necessary view hooks for the plugin.
     */
    private function registerHooks()
    {
        Tino::hook('navbar:items', NavbarItemsHook::class);
        Tino::hook('app:styles', StylesHook::class);
        Tino::hook('app:scripts', ScriptsHook::class);
    }

    /**
     * Publish public assets.
     *
     * @return void
     */
    protected function publishAssets()
    {
        $this->publishes([
            realpath(__DIR__.'/../dist') => $this->app['path.public'].'/vendor/plugins/announcements',
        ], 'public');
    }
}
