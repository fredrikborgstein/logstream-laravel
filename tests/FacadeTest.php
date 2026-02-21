<?php

use Logstream\Laravel\Facades\Logstream;
use Logstream\Laravel\Tests\TestCase;
use Illuminate\Support\Facades\Http;

uses(TestCase::class);

it('can call info() via the Facade', function () {
    Http::fake([
        'logger.borgstein.io/api/logs' => Http::response(['id' => 'x'], 201),
    ]);

    Logstream::info('Hello from Facade', ['key' => 'value']);

    Http::assertSent(fn ($r) =>
        $r['level'] === 'INFO' &&
        $r['message'] === 'Hello from Facade'
    );
});
