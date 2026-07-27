<?php declare(strict_types=1);

namespace PayKit\Payload\Requests;

use JsonSerializable;

final readonly class LedgerQuery implements JsonSerializable
{
    public function __construct(
        public ?string $accountId = null,
        public ?string $fromDate = null, // ISO
        public ?string $toDate = null,   // ISO
        public int $limit = 50,
        public ?string $cursor = null,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'accountId' => $this->accountId,
            'fromDate' => $this->fromDate,
            'toDate' => $this->toDate,
            'limit' => $this->limit,
            'cursor' => $this->cursor,
        ];
    }
}
