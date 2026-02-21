<?php

use Logstream\Laravel\LogstreamClient;
use Logstream\Laravel\Tests\TestCase;
use Illuminate\Support\Facades\Http;

uses(TestCase::class);

beforeEach(function () {
    Http::fake([
        'logger.borgstein.io/api/logs' => Http::response(['id' => 'abc123'], 201),
    ]);
});

it('sends a POST request to /api/logs with the correct headers', function () {
    $client = new LogstreamClient('test-api-key', 'https://logger.borgstein.io');
    $client->send('INFO', 'Hello world');

    Http::assertSent(function ($request) {
        return $request->url() === 'https://logger.borgstein.io/api/logs'
            && $request->method() === 'POST'
            && $request->header('x-api-key')[0] === 'test-api-key'
            && $request['level'] === 'INFO'
            && $request['message'] === 'Hello world';
    });
});

it('includes metadata when provided', function () {
    $client = new LogstreamClient('test-api-key', 'https://logger.borgstein.io');
    $client->send('ERROR', 'Payment failed', ['orderId' => 'ord_99']);

    Http::assertSent(function ($request) {
        return $request['metadata'] === ['orderId' => 'ord_99'];
    });
});

it('omits metadata key when null', function () {
    $client = new LogstreamClient('test-api-key', 'https://logger.borgstein.io');
    $client->send('DEBUG', 'test');

    Http::assertSent(function ($request) {
        return !isset($request['metadata']);
    });
});

it('does not throw on network error', function () {
    Http::fake(function () {
        throw new \Illuminate\Http\Client\ConnectionException('Connection refused');
    });

    $client = new LogstreamClient('test-key', 'https://logger.borgstein.io');

    expect(fn () => $client->send('INFO', 'test'))->not->toThrow(\Throwable::class);
});
