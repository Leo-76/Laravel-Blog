<?php

namespace CafeHistoire\CafeBlog;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Blade;
use CafeHistoire\CafeBlog\Services\CafeBlogService;
use CafeHistoire\CafeBlog\Http\Middleware\TrackPostViews;
use CafeHistoire\CafeBlog\Console\Commands\InstallCommand;
use CafeHistoire\CafeBlog\Console\Commands\PublishAssetsCommand;

class CafeBlogServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/cafe-blog.php',
            'cafe-blog'
        );

        $this->app->singleton(CafeBlogService::class, function ($app) {
            return new CafeBlogService(
                $app['config']->get('cafe-blog')
            );
        });

        $this->app->alias(CafeBlogService::class, 'cafe-blog');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'cafe-blog');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'cafe-blog');

        $this->registerRoutes();
        $this->registerBladeComponents();
        $this->registerBladeDirectives();
        $this->registerMiddleware();

        if ($this->app->runningInConsole()) {
            $this->registerConsoleCommands();
            $this->publishResources();
        }
    }

    /**
     * Register the package routes.
     */
    protected function registerRoutes(): void
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        });

        Route::group(array_merge($this->routeConfiguration(), ['prefix' => 'api']), function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        });
    }

    /**
     * Get the route group configuration.
     */
    protected function routeConfiguration(): array
    {
        return [
            'middleware' => config('cafe-blog.middleware', ['web']),
            'prefix' => config('cafe-blog.prefix', 'blog'),
        ];
    }

    /**
     * Register Blade components.
     */
    protected function registerBladeComponents(): void
    {
        Blade::componentNamespace('CafeHistoire\\CafeBlog\\View\\Components', 'cafe-blog');

        Blade::component('cafe-blog::components.post-card', 'cafe-blog-post-card');
        Blade::component('cafe-blog::components.category-badge', 'cafe-blog-category-badge');
        Blade::component('cafe-blog::components.author-avatar', 'cafe-blog-author-avatar');
        Blade::component('cafe-blog::components.coffee-bean-divider', 'cafe-blog-divider');
        Blade::component('cafe-blog::components.reading-progress', 'cafe-blog-reading-progress');
    }

    /**
     * Register custom Blade directives.
     */
    protected function registerBladeDirectives(): void
    {
        Blade::directive('coffeeOrigin', function ($expression) {
            return "<?php echo \CafeHistoire\CafeBlog\Helpers\CoffeeHelper::formatOrigin($expression); ?>";
        });

        Blade::directive('brewingTime', function ($expression) {
            return "<?php echo \CafeHistoire\CafeBlog\Helpers\CoffeeHelper::formatBrewingTime($expression); ?>";
        });

        Blade::directive('readingTime', function ($expression) {
            return "<?php echo \CafeHistoire\CafeBlog\Helpers\CoffeeHelper::readingTime($expression); ?>";
        });
    }

    /**
     * Register middleware.
     */
    protected function registerMiddleware(): void
    {
        $this->app['router']->aliasMiddleware(
            'cafe-blog.track-views',
            TrackPostViews::class
        );
    }

    /**
     * Register console commands.
     */
    protected function registerConsoleCommands(): void
    {
        $this->commands([
            InstallCommand::class,
            PublishAssetsCommand::class,
        ]);
    }

    /**
     * Publish package resources.
     */
    protected function publishResources(): void
    {
        // Config
        $this->publishes([
            __DIR__ . '/../config/cafe-blog.php' => config_path('cafe-blog.php'),
        ], 'cafe-blog-config');

        // Views
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/cafe-blog'),
        ], 'cafe-blog-views');

        // Assets (CSS, JS, images)
        $this->publishes([
            __DIR__ . '/../public' => public_path('vendor/cafe-blog'),
        ], 'cafe-blog-assets');

        // Migrations
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'cafe-blog-migrations');

        // Language files
        $this->publishes([
            __DIR__ . '/../resources/lang' => lang_path('vendor/cafe-blog'),
        ], 'cafe-blog-lang');

        // Seeders
        $this->publishes([
            __DIR__ . '/../database/seeders' => database_path('seeders'),
        ], 'cafe-blog-seeders');

        // Publish all at once
        $this->publishes([
            __DIR__ . '/../config/cafe-blog.php'   => config_path('cafe-blog.php'),
            __DIR__ . '/../resources/views'         => resource_path('views/vendor/cafe-blog'),
            __DIR__ . '/../public'                  => public_path('vendor/cafe-blog'),
            __DIR__ . '/../database/migrations'     => database_path('migrations'),
            __DIR__ . '/../resources/lang'          => lang_path('vendor/cafe-blog'),
        ], 'cafe-blog');
    }
}
