<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

use JsonSerializable;

final readonly class VirtualCardRecord implements JsonSerializable
{
    /**
     * @param array<string,mixed> $meta
     * @param array<string,mixed>|string|null $rawProviderPayload
     */
    public function __construct(
        public string $id,
        public VirtualCardStatus $status,
        public ?string $cardholderName = null,
        public ?string $last4 = null,
        public ?CardBrand $brand = null,
        public ?Currency $currency = null,
        public ?string $balanceAccountId = null,
        public ?string $expiresAt = null, // MM/YY or ISO
        public ?CardControls $controls = null,
        public array $meta = [],
        public array|string|null $rawProviderPayload = null,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status->value,
            'cardholderName' => $this->cardholderName,
            'last4' => $this->last4,
            'brand' => $this->brand?->value,
            'currency' => $this->currency?->toString(),
            'balanceAccountId' => $this->balanceAccountId,
            'expiresAt' => $this->expiresAt,
            'controls' => $this->controls?->jsonSerialize(),
            'meta' => $this->meta,
            'rawProviderPayload' => $this->rawProviderPayload,
        ];
    }
}
