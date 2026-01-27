<?php declare(strict_types=1);

namespace PayKit\Payload\Requests;

use JsonSerializable;
use PayKit\Payload\Common\Metadata;

final readonly class ReconcileQuery implements JsonSerializable
{
    public Metadata $meta;

    /**
     * Generic reconcile window (payments, virtual accounts, payouts, etc.)
     * @param array<string,mixed> $context
     */
    public function __construct(
        public ?string $from = null, // ISO string
        public ?string $to = null,   // ISO string
        public ?int    $limit = 200,
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
            'from' => $this->from,
            'to' => $this->to,
            'limit' => $this->limit,
            'cursor' => $this->cursor,
            'meta' => $this->meta->toArray(),
            'context' => $this->context,
        ];
    }
}

