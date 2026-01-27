<?php declare(strict_types=1);

namespace PayKit\Payload\Responses;

use JsonSerializable;

final class ReconcileResult implements JsonSerializable
{
    /**
     * Generic reconcile output: host can persist cursor + summary.
     * @param array<int,array<string,mixed>> $items normalized items (opaque to SDK)
     */
    public function __construct(
        public readonly array   $items = [],
        public readonly ?string $cursor = null,
        public readonly ?string $message = null,
    )
    {
    }

    public function jsonSerialize(): array
    {
        return [
            'items' => $this->items,
            'cursor' => $this->cursor,
            'message' => $this->message,
        ];
    }
}

