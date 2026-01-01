<?php declare(strict_types=1);

namespace PayKit\Payload\Responses;

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
        public string            $token,
        public ?NextAction       $nextAction = null,
        public array|string|null $rawProviderPayload = null,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'token' => $this->token,
            'nextAction' => $this->nextAction?->jsonSerialize(),
            'rawProviderPayload' => $this->rawProviderPayload,
        ];
    }
}

