<?php declare(strict_types=1);

namespace PayKit\Payload\Responses;

use JsonSerializable;
use PayKit\Payload\Common\CanonicalRefundStatus;
use PayKit\Payload\Common\ProviderRef;
use PayKit\Payload\Common\Reference;

final readonly class RefundStatusResult implements JsonSerializable
{
    /**
     * @param array<string,mixed>|string|null $rawProviderPayload
     */
    public function __construct(
        public ?Reference            $reference,
        public ?ProviderRef          $providerRef,
        public CanonicalRefundStatus $status,
        public array|string|null     $rawProviderPayload = null,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'reference' => $this->reference?->toString(),
            'providerRef' => $this->providerRef?->toString(),
            'status' => $this->status->value,
            'rawProviderPayload' => $this->rawProviderPayload,
        ];
    }
}

