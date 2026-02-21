<?php

namespace Logstream\Laravel;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendLogJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly string $level,
        private readonly string $message,
        private readonly ?array $metadata = null,
    ) {}

    public function handle(LogstreamClient $client): void
    {
        $client->send($this->level, $this->message, $this->metadata);
    }
}
