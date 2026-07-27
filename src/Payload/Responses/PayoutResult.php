<?php declare(strict_types=1);

namespace PayKit\Payload\Responses;

use Elqora\Interactions\Contracts\Interaction;
use JsonSerializable;
use PayKit\Payload\Common\CanonicalPayoutStatus;
use PayKit\Payload\Common\GatewayFailure;
use PayKit\Payload\Common\Money;
use PayKit\Payload\Common\ProviderRef;
use PayKit\Payload\Common\Reference;

final readonly class PayoutResult implements JsonSerializable
{
    /**
     * @param array<string,mixed>|string|null $rawProviderPayload
     */
    public function __construct(
        public Reference $reference,
        public ?ProviderRef $providerRef,
        public CanonicalPayoutStatus $status,
        public ?Money $requestedAmount = null,
        public ?Money $debitedAmount = null,
        public ?Money $fee = null,
        public ?Money $netAmount = null,
        public ?float $fxRate = null,
        public ?string $estimatedArrival = null, // ISO string
        public ?Interaction $interaction = null,
        public ?GatewayFailure $failure = null,
        public array|string|null $rawProviderPayload = null,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'reference' => $this->reference->toString(),
            'providerRef' => $this->providerRef?->toString(),
            'status' => $this->status->value,
            'requestedAmount' => $this->requestedAmount?->toArray(),
            'debitedAmount' => $this->debitedAmount?->toArray(),
            'fee' => $this->fee?->toArray(),
            'netAmount' => $this->netAmount?->toArray(),
            'fxRate' => $this->fxRate,
            'estimatedArrival' => $this->estimatedArrival,
            'interaction' => $this->interaction?->toArray(),
            'failure' => $this->failure?->jsonSerialize(),
            'rawProviderPayload' => $this->rawProviderPayload,
        ];
    }
}
