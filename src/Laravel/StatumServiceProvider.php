<?php

declare(strict_types=1);

namespace Statum\Sdk\Laravel;

use Illuminate\Support\ServiceProvider;
use Statum\Sdk\Config\StatumConfig;
use Statum\Sdk\StatumClient;

final class StatumServiceProvider extends ServiceProvider
{
    /**
     * Register any package services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/statum.php',
            'statum'
        );

        $this->app->singleton(StatumClient::class, static function ($app) {
            $config = new StatumConfig(
                consumerKey: (string) $app['config']['statum.consumer_key'],
                consumerSecret: (string) $app['config']['statum.consumer_secret'],
                baseUrl: (string) $app['config']['statum.base_url'],
                timeout: (float) $app['config']['statum.timeout']
            );

            return new StatumClient($config);
        });
    }

    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/statum.php' => $this->app->configPath('statum.php'),
            ], 'statum-config');
        }
    }
}
