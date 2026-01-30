<?php declare(strict_types=1);

namespace PayKit\Payload\Events;

use JsonSerializable;
use PayKit\Payload\Common\CanonicalRefundStatus;
use PayKit\Payload\Common\Metadata;
use PayKit\Payload\Common\Money;
use PayKit\Payload\Common\ProviderRef;
use PayKit\Payload\Common\Reference;

final readonly class RefundEvent implements JsonSerializable
{
    public Metadata $meta;

    /**
     * @param array<string,mixed>|string|null $rawProviderPayload
     */
    public function __construct(
        public string                 $type, // "created"|"updated"|"succeeded"|"failed"|...

        public ?Reference             $reference = null,
        public ?ProviderRef           $providerRef = null,

        public ?CanonicalRefundStatus $status = null,
        public ?Money                 $money = null,

        public ?Reference             $paymentReference = null,
        public ?ProviderRef           $paymentProviderRef = null,

        public ?string                $occurredAt = null, // ISO
        ?Metadata                     $meta = null,
        public array|string|null      $rawProviderPayload = null,
    )
    {
        $this->meta = $meta ?? new Metadata([]);
    }

    public function jsonSerialize(): array
    {
        return [
            'type' => $this->type,
            'reference' => $this->reference?->toString(),
            'providerRef' => $this->providerRef?->toString(),
            'status' => $this->status?->value,
            'money' => $this->money?->toArray(),
            'paymentReference' => $this->paymentReference?->toString(),
            'paymentProviderRef' => $this->paymentProviderRef?->toString(),
            'occurredAt' => $this->occurredAt,
            'meta' => $this->meta->toArray(),
            'rawProviderPayload' => $this->rawProviderPayload,
        ];
    }
}

