<?php declare(strict_types=1);

namespace PayKit\Payload\Requests;

use JsonSerializable;
use PayKit\Payload\Common\IdempotencyKey;
use PayKit\Payload\Common\Metadata;
use PayKit\Payload\Common\Money;
use PayKit\Payload\Common\PayoutDestination;
use PayKit\Payload\Common\PayoutSource;
use PayKit\Payload\Common\Reference;

final readonly class PayoutRequest implements JsonSerializable
{
    public Metadata $meta;

    /**
     * @param array<string,mixed> $context
     */
    public function __construct(
        public Reference $reference,
        public Money $money,
        public ?PayoutDestination $destination = null,
        public ?string $beneficiaryId = null,
        public ?PayoutSource $source = null,
        public ?IdempotencyKey $idempotencyKey = null,
        public ?string $narration = null,
        ?Metadata $meta = null,
        public array $context = [],
    ) {
        $this->meta = $meta ?? new Metadata([]);
    }

    public function jsonSerialize(): array
    {
        return [
            'reference' => $this->reference->toString(),
            'money' => $this->money->toArray(),
            'destination' => $this->destination?->jsonSerialize(),
            'beneficiaryId' => $this->beneficiaryId,
            'source' => $this->source?->jsonSerialize(),
            'idempotencyKey' => $this->idempotencyKey?->toString(),
            'narration' => $this->narration,
            'meta' => $this->meta->toArray(),
            'context' => $this->context,
        ];
    }
}
