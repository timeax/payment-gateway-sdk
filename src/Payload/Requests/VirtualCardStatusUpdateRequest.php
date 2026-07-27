<?php declare(strict_types=1);

namespace PayKit\Payload\Requests;

use JsonSerializable;
use PayKit\Payload\Common\VirtualCardStatus;

final readonly class VirtualCardStatusUpdateRequest implements JsonSerializable
{
    public function __construct(
        public string $cardId,
        public VirtualCardStatus $status, // e.g. active, frozen, terminated
        public ?string $reason = null,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'cardId' => $this->cardId,
            'status' => $this->status->value,
            'reason' => $this->reason,
        ];
    }
}
