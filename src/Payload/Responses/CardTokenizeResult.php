<?php declare(strict_types=1);

namespace PayKit\Payload\Responses;

use Elqora\Interactions\Contracts\Interaction;
use JsonSerializable;

final readonly class CardTokenizeResult implements JsonSerializable
{
    /**
     * Returned token can be:
     * - provider card token
     * - setup intent id
     * - hosted tokenization session id
     *
     * @param array<string,mixed>|string|null $rawProviderPayload
     */
    public function __construct(
        public string $token,
        public ?Interaction $interaction = null,
        public array|string|null $rawProviderPayload = null,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'token' => $this->token,
            'interaction' => $this->interaction?->toArray(),
            'rawProviderPayload' => $this->rawProviderPayload,
        ];
    }
}
