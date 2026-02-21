<?php

namespace Logstream\Laravel;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;

class LogstreamLogger implements LoggerInterface
{
    use LoggerTrait;

    private const LEVEL_MAP = [
        'debug'     => 'DEBUG',
        'info'      => 'INFO',
        'notice'    => 'INFO',
        'warning'   => 'WARN',
        'error'     => 'ERROR',
        'critical'  => 'ERROR',
        'alert'     => 'ERROR',
        'emergency' => 'ERROR',
    ];

    private const LEVEL_ORDER = [
        'debug'     => 0,
        'info'      => 1,
        'notice'    => 1,
        'warning'   => 2,
        'warn'      => 2,
        'error'     => 3,
        'critical'  => 3,
        'alert'     => 3,
        'emergency' => 3,
    ];

    public function __construct(
        private readonly LogstreamClient $client,
        private readonly bool $async,
        private readonly string $minLevel = 'debug',
    ) {}

    public function log($level, \Stringable|string $message, array $context = []): void
    {
        $normalizedLevel = strtolower((string) $level);
        $minOrder = self::LEVEL_ORDER[$this->minLevel] ?? 0;
        $thisOrder = self::LEVEL_ORDER[$normalizedLevel] ?? 0;
        if ($thisOrder < $minOrder) {
            return;
        }

        $mappedLevel = self::LEVEL_MAP[$normalizedLevel] ?? 'INFO';
        $metadata    = empty($context) ? null : $context;

        if ($this->async) {
            SendLogJob::dispatch($mappedLevel, (string) $message, $metadata);
            return;
        }

        $this->client->send($mappedLevel, (string) $message, $metadata);
    }

    /** Called by Laravel's log channel factory. */
    public function __invoke(array $config): self
    {
        return $this;
    }
}
