<?php declare(strict_types=1);

namespace PayKit\Payload\Events;

use JsonSerializable;

final readonly class WebhookHandleResult implements JsonSerializable
{
    /**
     * Represents what the host plans to return to the provider after handling.
     * @param array<string,string> $headers
     */
    public function __construct(
        public bool   $ack = true,
        public int    $statusCode = 200,
        public string $body = 'ok',
        public array  $headers = [],
    )
    {
    }

    public function jsonSerialize(): array
    {
        return [
            'ack' => $this->ack,
            'statusCode' => $this->statusCode,
            'body' => $this->body,
            'headers' => $this->headers,
        ];
    }
}