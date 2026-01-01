<?php declare(strict_types=1);

namespace PayKit\Payload\Requests;

use JsonSerializable;
use PayKit\Payload\Common\Metadata;

final readonly class PollVirtualAccountEventsQuery implements JsonSerializable
{
    public Metadata $meta;

    /**
     * Polling cursor/offset is provider-defined but carried by the host.
     * @param array<string,mixed> $context
     */
    public function __construct(
        public ?string $cursor = null,
        public ?int    $limit = 100,
        public ?string $since = null, // ISO string (optional)
        public ?string $until = null, // ISO string (optional)
        ?Metadata      $meta = null,
        public array   $context = [],
    )
    {
        $this->meta = $meta ?? new Metadata([]);
    }

    public function jsonSerialize(): array
    {
        return [
            'cursor' => $this->cursor,
            'limit' => $this->limit,
            'since' => $this->since,
            'until' => $this->until,
            'meta' => $this->meta->toArray(),
            'context' => $this->context,
        ];
    }
}