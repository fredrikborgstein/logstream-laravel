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

    public function __construct(
        private readonly LogstreamClient $client,
        private readonly bool $async,
    ) {}

    public function log($level, \Stringable|string $message, array $context = []): void
    {
        $mappedLevel = self::LEVEL_MAP[strtolower((string) $level)] ?? 'INFO';
        $metadata    = empty($context) ? null : $context;

        if ($this->async) {
            SendLogJob::dispatch($this->client->getApiKey(), $this->client->getBaseUrl(), $mappedLevel, (string) $message, $metadata);
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
