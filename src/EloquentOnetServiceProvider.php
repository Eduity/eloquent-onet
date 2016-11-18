<?php

namespace Eduity\EloquentOnet;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class EloquentOnetServiceProvider extends LaravelServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->handleConfigs();
        // $this->handleMigrations();
        // $this->handleViews();
        // $this->handleTranslations();
        // $this->handleRoutes();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Bind any implementations.
        $this->app->register(\Chumper\Zipper\ZipperServiceProvider::class);
        $this->app->register(\Collective\Bus\BusServiceProvider::class);
        $this->app->register(\Maatwebsite\Excel\ExcelServiceProvider::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    private function handleConfigs()
    {
        $configPath = __DIR__ . '/../config/eloquent-onet.php';

        $this->publishes([$configPath => config_path('eloquent-onet.php')]);

        $this->mergeConfigFrom($configPath, 'eloquent-onet');
    }

    private function handleTranslations()
    {
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'eloquent-onet');
    }

    private function handleViews()
    {
        $this->loadViewsFrom(__DIR__.'/../views', 'eloquent-onet');

        $this->publishes([__DIR__.'/../views' => base_path('resources/views/vendor/eloquent-onet')]);
    }

    private function handleMigrations()
    {
        $this->publishes([__DIR__ . '/../migrations' => base_path('database/migrations')]);
    }

    private function handleRoutes()
    {
        include __DIR__.'/../routes.php';
    }
}
