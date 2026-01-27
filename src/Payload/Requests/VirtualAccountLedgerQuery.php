<?php declare(strict_types=1);

namespace PayKit\Payload\Requests;

use JsonSerializable;
use PayKit\Payload\Common\Metadata;

final readonly class VirtualAccountLedgerQuery implements JsonSerializable
{
    public Metadata $meta;

    /**
     * Dates are ISO strings (host-controlled).
     * @param array<string,mixed> $context
     */
    public function __construct(
        public string  $virtualAccountId,
        public ?string $from = null,
        public ?string $to = null,
        public ?int    $limit = 50,
        public ?string $cursor = null,
        ?Metadata      $meta = null,
        public array   $context = [],
    ) {
        $this->meta = $meta ?? new Metadata([]);
    }

    public function jsonSerialize(): array
    {
        return [
            'virtualAccountId' => $this->virtualAccountId,
            'from' => $this->from,
            'to' => $this->to,
            'limit' => $this->limit,
            'cursor' => $this->cursor,
            'meta' => $this->meta->toArray(),
            'context' => $this->context,
        ];
    }
}

