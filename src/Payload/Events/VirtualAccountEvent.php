<?php declare(strict_types=1);

namespace PayKit\Payload\Events;

use JsonSerializable;
use PayKit\Payload\Common\Metadata;
use PayKit\Payload\Common\Money;
use PayKit\Payload\Common\ProviderRef;
use PayKit\Payload\Common\Reference;

final readonly class VirtualAccountEvent implements JsonSerializable
{
    public Metadata $meta;

    /**
     * @param array<string,mixed>|string|null $rawProviderPayload
     */
    public function __construct(
        public VirtualAccountEventType $type,

        public string                  $virtualAccountId,

        public ?string                 $ledgerEntryId = null,

        public ?Money                  $amount = null,

        public ?Reference              $reference = null,
        public ?ProviderRef            $providerRef = null,

        public ?string                 $occurredAt = null, // ISO string
        ?Metadata                      $meta = null,
        public array|string|null       $rawProviderPayload = null,
    )
    {
        $this->meta = $meta ?? new Metadata([]);
    }

    public function jsonSerialize(): array
    {
        return [
            'type' => $this->type->value,
            'virtualAccountId' => $this->virtualAccountId,
            'ledgerEntryId' => $this->ledgerEntryId,
            'amount' => $this->amount?->toArray(),
            'reference' => $this->reference?->toString(),
            'providerRef' => $this->providerRef?->toString(),
            'occurredAt' => $this->occurredAt,
            'meta' => $this->meta->toArray(),
            'rawProviderPayload' => $this->rawProviderPayload,
        ];
    }
}