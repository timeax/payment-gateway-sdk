<?php declare(strict_types=1);

namespace PayKit\Payload\Responses;

use JsonSerializable;
use PayKit\Payload\Common\VirtualAccount;

final readonly class VirtualAccountList implements JsonSerializable
{
    /**
     * @param array<int,VirtualAccount> $items
     */
    public function __construct(
        public array   $items = [],
        public ?string $cursor = null,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'items' => array_map(static fn (VirtualAccount $v) => $v->jsonSerialize(), $this->items),
            'cursor' => $this->cursor,
        ];
    }
}



