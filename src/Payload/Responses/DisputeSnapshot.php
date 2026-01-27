<?php declare(strict_types=1);

namespace PayKit\Payload\Responses;

use JsonSerializable;
use PayKit\Payload\Common\Metadata;
use PayKit\Payload\Common\Money;
use PayKit\Payload\Common\ProviderRef;

final readonly class DisputeSnapshot implements JsonSerializable
{
    public Metadata $meta;

    /**
     * @param array<string,mixed>|string|null $rawProviderPayload
     */
    public function __construct(
        public ProviderRef       $providerRef,
        public ?string           $status = null,
        public ?Money            $amount = null,
        public ?string           $reason = null,
        public ?string           $openedAt = null,   // ISO
        public ?string           $updatedAt = null,  // ISO
        ?Metadata                $meta = null,
        public array|string|null $rawProviderPayload = null,
    ) {
        $this->meta = $meta ?? new Metadata([]);
    }

    public function jsonSerialize(): array
    {
        return [
            'providerRef' => $this->providerRef->toString(),
            'status' => $this->status,
            'amount' => $this->amount?->toArray(),
            'reason' => $this->reason,
            'openedAt' => $this->openedAt,
            'updatedAt' => $this->updatedAt,
            'meta' => $this->meta->toArray(),
            'rawProviderPayload' => $this->rawProviderPayload,
        ];
    }
}



