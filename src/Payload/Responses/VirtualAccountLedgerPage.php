<?php declare(strict_types=1);

namespace PayKit\Payload\Responses;

use JsonSerializable;

final readonly class VirtualAccountLedgerPage implements JsonSerializable
{
    /**
     * @param array<int,VirtualAccountLedgerEntry> $items
     */
    public function __construct(
        public array   $items = [],
        public ?string $cursor = null,
    )
    {
    }

    public function jsonSerialize(): array
    {
        return [
            'items' => array_map(static fn(VirtualAccountLedgerEntry $e) => $e->jsonSerialize(), $this->items),
            'cursor' => $this->cursor,
        ];
    }
}

