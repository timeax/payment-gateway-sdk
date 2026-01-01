<?php declare(strict_types=1);

namespace PayKit\Payload\Requests;

use JsonSerializable;
use PayKit\Payload\Common\Metadata;

final readonly class ListVirtualAccountsRequest implements JsonSerializable
{
    public Metadata $meta;

    /**
     * @param array<string,mixed> $context
     */
    public function __construct(
        public ?string $ownerKey = null,
        public ?int    $limit = 50,
        public ?string $cursor = null,
        ?Metadata      $meta = null,
        public array   $context = [],
    )
    {
        $this->meta = $meta ?? new Metadata([]);
    }

    public function jsonSerialize(): array
    {
        return [
            'ownerKey' => $this->ownerKey,
            'limit' => $this->limit,
            'cursor' => $this->cursor,
            'meta' => $this->meta->toArray(),
            'context' => $this->context,
        ];
    }
}