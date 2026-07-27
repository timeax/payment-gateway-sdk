<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

use JsonSerializable;

final readonly class VirtualAccount implements JsonSerializable
{
    /**
     * @param array<string,mixed> $meta
     * @param array<string,mixed>|string|null $rawProviderPayload
     */
    public function __construct(
        public string $id, // provider account ID or stable identifier
        public Reference $reference,
        public string $ownerKey,
        public VirtualAccountPurpose $purpose,
        public VirtualAccountUsage $usage,
        public VirtualAccountStatus $status,
        public ?string $accountName = null,
        public ?string $accountNumber = null,
        public ?VirtualAccountBank $bank = null,
        public ?Currency $currency = null,
        public ?Country $country = null,
        public ?CustomerIdentity $customer = null,
        public ?Money $expectedAmount = null,
        public ?string $expiresAt = null, // ISO string
        public ?string $createdAt = null, // ISO string
        public array $meta = [],
        public array|string|null $rawProviderPayload = null,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'reference' => $this->reference->toString(),
            'ownerKey' => $this->ownerKey,
            'purpose' => $this->purpose->value,
            'usage' => $this->usage->value,
            'status' => $this->status->value,
            'accountName' => $this->accountName,
            'accountNumber' => $this->accountNumber,
            'bank' => $this->bank?->jsonSerialize(),
            'currency' => $this->currency?->toString(),
            'country' => $this->country?->toString(),
            'customer' => $this->customer?->jsonSerialize(),
            'expectedAmount' => $this->expectedAmount?->toArray(),
            'expiresAt' => $this->expiresAt,
            'createdAt' => $this->createdAt,
            'meta' => $this->meta,
            'rawProviderPayload' => $this->rawProviderPayload,
        ];
    }
}
