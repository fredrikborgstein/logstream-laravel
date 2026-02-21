<?php

namespace Logstream\Laravel\Tests;

use Logstream\Laravel\LogstreamServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [LogstreamServiceProvider::class];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('logstream.api_key', 'test-key');
        $app['config']->set('logstream.base_url', 'https://logger.borgstein.io');
        $app['config']->set('logstream.async', false);
    }
}
