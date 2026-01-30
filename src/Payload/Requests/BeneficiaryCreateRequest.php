<?php declare(strict_types=1);

namespace PayKit\Payload\Requests;

use JsonSerializable;
use PayKit\Payload\Common\Metadata;
use PayKit\Payload\Common\PayoutDestination;

final readonly class BeneficiaryCreateRequest implements JsonSerializable
{
    public Metadata $meta;

    /**
     * @param array<string,mixed> $context
     */
    public function __construct(
        public string            $name,
        public PayoutDestination $destination,
        ?Metadata                $meta = null,
        public array             $context = [],
    )
    {
        $this->meta = $meta ?? new Metadata([]);
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'destination' => $this->destination->jsonSerialize(),
            'meta' => $this->meta->toArray(),
            'context' => $this->context,
        ];
    }
}

