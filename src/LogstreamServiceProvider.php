<?php

namespace Logstream\Laravel;

use Illuminate\Support\ServiceProvider;

class LogstreamServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/logstream.php', 'logstream');

        $this->app->singleton(LogstreamClient::class, function ($app) {
            return new LogstreamClient(
                $app['config']['logstream.api_key'],
                $app['config']['logstream.base_url'],
            );
        });

        $this->app->singleton('logstream', function ($app) {
            return new LogstreamLogger(
                $app->make(LogstreamClient::class),
                (bool) $app['config']['logstream.async'],
            );
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/logstream.php' => config_path('logstream.php'),
            ], 'logstream-config');
        }
    }
}
