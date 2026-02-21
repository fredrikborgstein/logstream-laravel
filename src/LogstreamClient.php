<?php

namespace Logstream\Laravel;

use Illuminate\Support\Facades\Http;
use Throwable;

class LogstreamClient
{
    public function __construct(
        private readonly string $apiKey,
        private readonly string $baseUrl,
    ) {}

    public function send(string $level, string $message, ?array $metadata = null): void
    {
        try {
            $payload = array_filter([
                'level'    => $level,
                'message'  => $message,
                'metadata' => $metadata,
            ], fn ($v) => $v !== null);

            Http::withHeaders(['x-api-key' => $this->apiKey])
                ->post("{$this->baseUrl}/api/logs", $payload);
        } catch (Throwable) {
            // Fire-and-forget: never crash the application
        }
    }

    public function getApiKey(): string { return $this->apiKey; }
    public function getBaseUrl(): string { return $this->baseUrl; }
}
