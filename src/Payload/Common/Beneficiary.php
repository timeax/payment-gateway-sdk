<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

use JsonSerializable;

final class Beneficiary implements JsonSerializable
{
    /**
     * @param array<string,mixed> $meta
     */
    public function __construct(
        public readonly string             $id,
        public readonly string             $name,
        public readonly ?PayoutDestination $destination = null,
        public readonly array              $meta = [],
    )
    {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'destination' => $this->destination?->jsonSerialize(),
            'meta' => $this->meta,
        ];
    }
}

