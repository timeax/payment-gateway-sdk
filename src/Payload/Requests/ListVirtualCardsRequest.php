<?php declare(strict_types=1);

namespace PayKit\Payload\Requests;

use JsonSerializable;

final readonly class ListVirtualCardsRequest implements JsonSerializable
{
    public function __construct(
        public ?string $balanceAccountId = null,
        public int $limit = 50,
        public ?string $cursor = null,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'balanceAccountId' => $this->balanceAccountId,
            'limit' => $this->limit,
            'cursor' => $this->cursor,
        ];
    }
}
