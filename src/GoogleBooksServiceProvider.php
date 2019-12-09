<?php

namespace Scriptotek\GoogleBooks;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Scriptotek\GoogleBooks\Exceptions\InvalidCOnfiguration;

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
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'googlebooks');

        $this->app->singleton(GoogleBooks::class, function () {
            $config = config('googlebooks');
            $this->validateConfig($config);
            return new GoogleBooks($config);
        });

        $this->app->alias(GoogleBooks::class, 'googlebooks');
    }

    protected function validateConfig(array $config = null)
    {
        if (empty($config['key'])) {
            throw InvalidConfiguration::keyNotSpecified();
        }
    }
}
