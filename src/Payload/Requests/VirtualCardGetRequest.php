<?php declare(strict_types=1);

namespace PayKit\Payload\Requests;

use JsonSerializable;

final readonly class VirtualCardGetRequest implements JsonSerializable
{
    public function __construct(
        public string $cardId,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'cardId' => $this->cardId,
        ];
    }
}
