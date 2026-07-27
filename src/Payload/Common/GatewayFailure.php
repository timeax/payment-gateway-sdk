<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

use JsonSerializable;

final readonly class GatewayFailure implements JsonSerializable
{
    /**
     * @param array<string,mixed> $details
     */
    public function __construct(
        public string $code,
        public string $message,
        public bool $retryable = false,
        public ?string $providerCode = null,
        public ?string $declineCode = null,
        public array $details = [],
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'code' => $this->code,
            'message' => $this->message,
            'retryable' => $this->retryable,
            'providerCode' => $this->providerCode,
            'declineCode' => $this->declineCode,
            'details' => $this->details,
        ];
    }
}
