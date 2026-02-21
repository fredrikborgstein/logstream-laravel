# logstream-laravel

Laravel client for [Logstream](https://logger.borgstein.io).

## Install

```bash
composer require borgstein/logstream-laravel
```

Service provider and Facade are auto-discovered.

## Configuration

Publish the config file:

```bash
php artisan vendor:publish --tag=logstream-config
```

Add to `.env`:

```env
LOGSTREAM_API_KEY=your_api_key_here
```

## Usage

### Option A — Facade

```php
use Logstream\Laravel\Facades\Logstream;

Logstream::info('User signed up', ['userId' => $user->id]);
Logstream::error('Payment failed', ['orderId' => $order->id]);
```

### Option B — Laravel Log channel

In `config/logging.php`:

```php
'channels' => [
    'logstream' => [
        'driver' => 'custom',
        'via'    => \Logstream\Laravel\LogstreamLogger::class,
        'level'  => 'debug',
    ],
],
```

Then use as any Laravel log channel:

```php
Log::channel('logstream')->info('User signed up');

// Or add to your stack:
'stack' => [
    'driver'   => 'stack',
    'channels' => ['daily', 'logstream'],
],
```

## Async logging

Set `LOGSTREAM_ASYNC=true` in `.env`. Logs will be dispatched as queued jobs — requires a configured queue driver.

## Level mapping

| Laravel | Logstream |
|---|---|
| `debug` | `DEBUG` |
| `info`, `notice` | `INFO` |
| `warning` | `WARN` |
| `error`, `critical`, `alert`, `emergency` | `ERROR` |
