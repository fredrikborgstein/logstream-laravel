<?php

namespace Logstream\Laravel;

use Illuminate\Support\ServiceProvider;

class LogstreamServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(LogstreamClient::class, function ($app) {
            $config = $app['config'];

            return new LogstreamClient(
                $config->get('logstream.api_key', ''),
                $config->get('logstream.base_url', 'https://logger.borgstein.io'),
            );
        });
    }

    public function boot(): void
    {
        //
    }
}
