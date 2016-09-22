<?php

namespace Scriptotek\GoogleBooks;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

/**
 * Laravel 5 service provider
 */
class GoogleBooksServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/config.php' => config_path('googlebooks.php')
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $app = $this->app;
        $this->mergeConfigFrom(
            __DIR__.'/../config/config.php',
            'googlebooks'
        );
        $this->app->singleton('googlebooks', function ($app) {
            $options = [];
            $options['key'] = $app['config']->get('googlebooks.key');
            $options['country'] = $app['config']->get('googlebooks.country');

            return new GoogleBooks($options);
        });

        $app->alias('googlebooks', GoogleBooks::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['googlebooks'];
    }
}
