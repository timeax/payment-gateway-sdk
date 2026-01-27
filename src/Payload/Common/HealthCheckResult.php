<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

use JsonSerializable;

final readonly class HealthCheckResult implements JsonSerializable
{
    /** @param array<string,mixed> $details */
    public function __construct(
        public bool    $ok,
        public ?string $message = null,
        public array   $details = [],
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'ok' => $this->ok,
            'message' => $this->message,
            'details' => $this->details,
        ];
    }
}

