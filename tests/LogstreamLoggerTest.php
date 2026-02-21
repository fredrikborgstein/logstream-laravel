<?php

use Logstream\Laravel\LogstreamLogger;
use Logstream\Laravel\LogstreamClient;
use Logstream\Laravel\Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;

uses(TestCase::class);

beforeEach(function () {
    Http::fake([
        'logger.borgstein.io/api/logs' => Http::response(['id' => 'abc'], 201),
    ]);
});

it('maps Laravel debug level to DEBUG', function () {
    $logger = new LogstreamLogger(app(LogstreamClient::class), false);
    $logger->debug('test debug', ['context' => 'val']);

    Http::assertSent(fn ($r) => $r['level'] === 'DEBUG' && $r['message'] === 'test debug');
});

it('maps Laravel info and notice levels to INFO', function () {
    $logger = new LogstreamLogger(app(LogstreamClient::class), false);
    $logger->info('test info');
    $logger->notice('test notice');

    Http::assertSentCount(2);
});

it('maps Laravel warning level to WARN', function () {
    $logger = new LogstreamLogger(app(LogstreamClient::class), false);
    $logger->warning('test warning');

    Http::assertSent(fn ($r) => $r['level'] === 'WARN');
});

it('maps Laravel error/critical/alert/emergency to ERROR', function () {
    $logger = new LogstreamLogger(app(LogstreamClient::class), false);
    $logger->error('err');
    $logger->critical('crit');
    $logger->alert('alert');
    $logger->emergency('emerg');

    Http::assertSentCount(4);
    Http::assertSent(fn ($r) => $r['level'] === 'ERROR');
});

it('passes context array as metadata', function () {
    $logger = new LogstreamLogger(app(LogstreamClient::class), false);
    $logger->info('with context', ['userId' => 42]);

    Http::assertSent(fn ($r) => $r['metadata'] === ['userId' => 42]);
});

it('dispatches a job when async is true', function () {
    Queue::fake();

    $logger = new LogstreamLogger(app(LogstreamClient::class), true);
    $logger->info('async message');

    Queue::assertPushed(\Logstream\Laravel\SendLogJob::class);
    Http::assertNothingSent();
});
