<?php declare(strict_types=1);

namespace PayKit\Payload\Responses;

use JsonSerializable;

final readonly class FrontendConfigResult implements JsonSerializable
{
    /** @param array<string,mixed> $config */
    public function __construct(
        public array   $config = [],
        public ?string $expiresAt = null, // ISO string
    )
    {
    }

    public function jsonSerialize(): array
    {
        return [
            'config' => $this->config,
            'expiresAt' => $this->expiresAt,
        ];
    }
}

