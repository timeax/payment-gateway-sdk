<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

use JsonSerializable;

final readonly class AccountTransaction implements JsonSerializable
{
    /**
     * @param array<string,mixed>|string|null $rawProviderPayload
     */
    public function __construct(
        public string $id,
        public string $accountId,
        public AccountTransactionType $type,
        public AccountTransactionDirection $direction,
        public Money $amount,
        public ?Money $fee = null,
        public ?Money $netAmount = null,
        public ?Money $runningBalance = null,
        public ?Reference $reference = null,
        public ?ProviderRef $providerRef = null,
        public ?string $description = null,
        public ?string $occurredAt = null, // ISO string
        public ?string $bookedAt = null,   // ISO string
        public ?string $valueAt = null,    // ISO string
        public array|string|null $rawProviderPayload = null,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'accountId' => $this->accountId,
            'type' => $this->type->value,
            'direction' => $this->direction->value,
            'amount' => $this->amount->toArray(),
            'fee' => $this->fee?->toArray(),
            'netAmount' => $this->netAmount?->toArray(),
            'runningBalance' => $this->runningBalance?->toArray(),
            'reference' => $this->reference?->toString(),
            'providerRef' => $this->providerRef?->toString(),
            'description' => $this->description,
            'occurredAt' => $this->occurredAt,
            'bookedAt' => $this->bookedAt,
            'valueAt' => $this->valueAt,
            'rawProviderPayload' => $this->rawProviderPayload,
        ];
    }
}
