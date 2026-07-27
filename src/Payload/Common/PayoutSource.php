<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

use JsonSerializable;

final readonly class PayoutSource implements JsonSerializable
{
    public function __construct(
        public PayoutSourceType $type,
        public ?string $sourceId = null,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'type' => $this->type->value,
            'sourceId' => $this->sourceId,
        ];
    }
}
