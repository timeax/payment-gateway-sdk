<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

use JsonSerializable;

final readonly class BulkPayoutItem implements JsonSerializable
{
    /**
     * @param array<string,mixed> $meta
     */
    public function __construct(
        public string $itemId,
        public Money $money,
        public ?PayoutDestinationPayload $destination = null,
        public ?string $beneficiaryId = null,
        public ?string $narration = null,
        public array $meta = [],
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'itemId' => $this->itemId,
            'money' => $this->money->toArray(),
            'destination' => $this->destination?->jsonSerialize(),
            'beneficiaryId' => $this->beneficiaryId,
            'narration' => $this->narration,
            'meta' => $this->meta,
        ];
    }
}
