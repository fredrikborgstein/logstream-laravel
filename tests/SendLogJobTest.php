<?php

use Logstream\Laravel\LogstreamClient;
use Logstream\Laravel\SendLogJob;
use Logstream\Laravel\Tests\TestCase;
use Illuminate\Support\Facades\Http;

uses(TestCase::class);

it('dispatches HTTP call via LogstreamClient when handled', function () {
    Http::fake([
        'logger.borgstein.io/api/logs' => Http::response(['id' => 'abc'], 201),
    ]);

    $job = new SendLogJob('WARN', 'test message', ['x' => 1]);
    $job->handle(new LogstreamClient('test-key', 'https://logger.borgstein.io'));

    Http::assertSent(function ($request) {
        return $request['level'] === 'WARN'
            && $request['message'] === 'test message'
            && $request['metadata'] === ['x' => 1];
    });
});
