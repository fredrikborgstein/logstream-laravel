<?php

namespace Logstream\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void debug(string $message, array $context = [])
 * @method static void info(string $message, array $context = [])
 * @method static void notice(string $message, array $context = [])
 * @method static void warning(string $message, array $context = [])
 * @method static void error(string $message, array $context = [])
 * @method static void critical(string $message, array $context = [])
 * @method static void alert(string $message, array $context = [])
 * @method static void emergency(string $message, array $context = [])
 */
class Logstream extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'logstream';
    }
}
