<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

use JsonSerializable;

final readonly class BalanceAccount implements JsonSerializable
{
    /**
     * @param array<string,mixed> $meta
     */
    public function __construct(
        public string $id,
        public ?string $name = null,
        public ?Currency $currency = null,
        public ?BalanceAmount $balances = null,
        public ?string $status = null,
        public array $meta = [],
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'currency' => $this->currency?->toString(),
            'balances' => $this->balances?->jsonSerialize(),
            'status' => $this->status,
            'meta' => $this->meta,
        ];
    }
}
