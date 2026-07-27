<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

use JsonSerializable;

final readonly class BalanceAmount implements JsonSerializable
{
    public function __construct(
        public Money $available,
        public ?Money $pending = null,
        public ?Money $reserved = null,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'available' => $this->available->toArray(),
            'pending' => $this->pending?->toArray(),
            'reserved' => $this->reserved?->toArray(),
        ];
    }
}
