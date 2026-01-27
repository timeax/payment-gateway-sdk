<?php declare(strict_types=1);

namespace PayKit\Payload\Requests;

use JsonSerializable;
use PayKit\Payload\Common\Metadata;
use PayKit\Payload\Common\PayoutDestination;

final readonly class BeneficiaryUpdateRequest implements JsonSerializable
{
    public Metadata $meta;

    /**
     * @param array<string,mixed> $context
     */
    public function __construct(
        public string             $beneficiaryId,
        public ?string            $name = null,
        public ?PayoutDestination $destination = null,
        ?Metadata                 $meta = null,
        public array              $context = [],
    )
    {
        $this->meta = $meta ?? new Metadata([]);
    }

    public function jsonSerialize(): array
    {
        return [
            'beneficiaryId' => $this->beneficiaryId,
            'name' => $this->name,
            'destination' => $this->destination?->jsonSerialize(),
            'meta' => $this->meta->toArray(),
            'context' => $this->context,
        ];
    }
}

