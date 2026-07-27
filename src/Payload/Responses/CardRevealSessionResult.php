<?php declare(strict_types=1);

namespace PayKit\Payload\Responses;

use JsonSerializable;

final readonly class CardRevealSessionResult implements JsonSerializable
{
    /**
     * PCI-Scope isolated ephemeral session or iframe specs.
     * @param array<string,mixed> $clientMetadata
     */
    public function __construct(
        public string $ephemeralToken,
        public ?string $hostedRevealUrl = null,
        public ?string $expiresAt = null, // ISO string
        public array $clientMetadata = [],
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'ephemeralToken' => $this->ephemeralToken,
            'hostedRevealUrl' => $this->hostedRevealUrl,
            'expiresAt' => $this->expiresAt,
            'clientMetadata' => $this->clientMetadata,
        ];
    }
}
