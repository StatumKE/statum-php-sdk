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
            $config = $app['config']->get('statum', []);

            $sdkConfig = new StatumConfig(
                consumerKey: (string) ($config['consumer_key'] ?? ''),
                consumerSecret: (string) ($config['consumer_secret'] ?? ''),
                baseUrl: (string) ($config['base_url'] ?? 'https://api.statum.co.ke/api/v2'),
                timeout: (float) ($config['timeout'] ?? 30.0)
            );

            return new StatumClient($sdkConfig);
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
