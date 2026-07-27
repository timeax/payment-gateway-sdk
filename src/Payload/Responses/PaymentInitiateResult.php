<?php declare(strict_types=1);

namespace PayKit\Payload\Responses;

use Elqora\Interactions\Contracts\Interaction;
use JsonSerializable;
use PayKit\Payload\Common\CanonicalPaymentStatus;
use PayKit\Payload\Common\ProviderRef;
use PayKit\Payload\Common\Reference;

final readonly class PaymentInitiateResult implements JsonSerializable
{
    /**
     * @param array<string,mixed>|string|null $rawProviderPayload
     */
    public function __construct(
        public Reference $reference,
        public ?ProviderRef $providerRef,
        public CanonicalPaymentStatus $status,
        public ?Interaction $interaction = null,
        public array|string|null $rawProviderPayload = null,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'reference' => $this->reference->toString(),
            'providerRef' => $this->providerRef?->toString(),
            'status' => $this->status->value,
            'interaction' => $this->interaction?->toArray(),
            'rawProviderPayload' => $this->rawProviderPayload,
        ];
    }
}
