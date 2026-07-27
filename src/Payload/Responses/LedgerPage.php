<?php declare(strict_types=1);

namespace PayKit\Payload\Responses;

use JsonSerializable;
use PayKit\Payload\Common\AccountTransaction;

final readonly class LedgerPage implements JsonSerializable
{
    /**
     * @param array<AccountTransaction> $items
     */
    public function __construct(
        public array $items,
        public ?string $nextCursor = null,
        public bool $hasMore = false,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'items' => array_map(
                static fn (AccountTransaction $t) => $t->jsonSerialize(),
                $this->items
            ),
            'nextCursor' => $this->nextCursor,
            'hasMore' => $this->hasMore,
        ];
    }
}
