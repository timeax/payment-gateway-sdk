<?php declare(strict_types=1);

namespace PayKit\Payload\Requests;

use JsonSerializable;
use PayKit\Payload\Common\CardControls;

final readonly class VirtualCardControlsRequest implements JsonSerializable
{
    public function __construct(
        public string $cardId,
        public CardControls $controls,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'cardId' => $this->cardId,
            'controls' => $this->controls->jsonSerialize(),
        ];
    }
}
