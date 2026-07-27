<?php declare(strict_types=1);

namespace PayKit\Payload\Requests;

use JsonSerializable;
use PayKit\Payload\Common\BulkPayoutItem;
use PayKit\Payload\Common\IdempotencyKey;
use PayKit\Payload\Common\Metadata;
use PayKit\Payload\Common\PayoutSource;
use PayKit\Payload\Common\Reference;

final readonly class BulkPayoutRequest implements JsonSerializable
{
    public Metadata $meta;

    /**
     * @param array<BulkPayoutItem> $items
     * @param array<string,mixed> $context
     */
    public function __construct(
        public Reference $reference,
        public array $items,
        public ?PayoutSource $source = null,
        public ?IdempotencyKey $idempotencyKey = null,
        public ?string $title = null,
        ?Metadata $meta = null,
        public array $context = [],
    ) {
        $this->meta = $meta ?? new Metadata([]);
    }

    public function jsonSerialize(): array
    {
        return [
            'reference' => $this->reference->toString(),
            'items' => array_map(
                static fn (BulkPayoutItem $item) => $item->jsonSerialize(),
                $this->items
            ),
            'source' => $this->source?->jsonSerialize(),
            'idempotencyKey' => $this->idempotencyKey?->toString(),
            'title' => $this->title,
            'meta' => $this->meta->toArray(),
            'context' => $this->context,
        ];
    }
}
