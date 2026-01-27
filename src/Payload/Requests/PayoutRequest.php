<?php declare(strict_types=1);

namespace PayKit\Payload\Requests;

use JsonSerializable;
use PayKit\Payload\Common\Metadata;
use PayKit\Payload\Common\Money;
use PayKit\Payload\Common\PayoutDestination;
use PayKit\Payload\Common\Reference;

final readonly class PayoutRequest implements JsonSerializable
{
    public Metadata $meta;

    /**
     * Either beneficiaryId OR destination can be provided (driver chooses).
     * @param array<string,mixed> $context
     */
    public function __construct(
        public Reference          $reference,
        public Money              $money,
        public ?string            $beneficiaryId = null,
        public ?PayoutDestination $destination = null,
        public ?string            $narration = null,
        ?Metadata                 $meta = null,
        public array              $context = [],
    )
    {
        $this->meta = $meta ?? new Metadata([]);
    }

    public function jsonSerialize(): array
    {
        return [
            'reference' => $this->reference->toString(),
            'money' => $this->money->toArray(),
            'beneficiaryId' => $this->beneficiaryId,
            'destination' => $this->destination?->jsonSerialize(),
            'narration' => $this->narration,
            'meta' => $this->meta->toArray(),
            'context' => $this->context,
        ];
    }
}

