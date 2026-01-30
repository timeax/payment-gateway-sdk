<?php declare(strict_types=1);

namespace PayKit\Payload\Responses;

use JsonSerializable;
use PayKit\Payload\Common\Money;
use PayKit\Payload\Common\ProviderRef;
use PayKit\Payload\Common\Reference;

final readonly class VirtualAccountLedgerEntry implements JsonSerializable
{
    /**
     * @param array<string,mixed>|string|null $rawProviderPayload
     */
    public function __construct(
        public string            $id,
        public Money             $amount,
        public ?string           $type = null, // "credit"|"debit"|provider-specific
        public ?string           $occurredAt = null, // ISO
        public ?Reference        $reference = null,
        public ?ProviderRef      $providerRef = null,
        public ?string           $description = null,
        public array|string|null $rawProviderPayload = null,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount->toArray(),
            'type' => $this->type,
            'occurredAt' => $this->occurredAt,
            'reference' => $this->reference?->toString(),
            'providerRef' => $this->providerRef?->toString(),
            'description' => $this->description,
            'rawProviderPayload' => $this->rawProviderPayload,
        ];
    }
}



