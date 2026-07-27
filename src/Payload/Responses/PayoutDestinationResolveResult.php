<?php declare(strict_types=1);

namespace PayKit\Payload\Responses;

use JsonSerializable;
use PayKit\Payload\Common\GatewayFailure;

final readonly class PayoutDestinationResolveResult implements JsonSerializable
{
    /**
     * @param array<string,mixed> $details
     * @param array<string,mixed>|string|null $rawProviderPayload
     */
    public function __construct(
        public bool $resolved,
        public ?string $accountName = null,
        public ?string $accountNumber = null,
        public ?string $bankCode = null,
        public array $details = [],
        public ?GatewayFailure $failure = null,
        public array|string|null $rawProviderPayload = null,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'resolved' => $this->resolved,
            'accountName' => $this->accountName,
            'accountNumber' => $this->accountNumber,
            'bankCode' => $this->bankCode,
            'details' => $this->details,
            'failure' => $this->failure?->jsonSerialize(),
            'rawProviderPayload' => $this->rawProviderPayload,
        ];
    }
}
