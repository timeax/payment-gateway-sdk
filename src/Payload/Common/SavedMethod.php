<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

use JsonSerializable;

final readonly class SavedMethod implements JsonSerializable
{
    /**
     * @param array<string,mixed> $meta
     */
    public function __construct(
        public string       $id,
        public string       $type, // "card"|"bank"|"wallet"|...
        public ?string      $label = null,
        public bool         $default = false,
        public ?CardSummary $card = null,
        public array        $meta = [],
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'label' => $this->label,
            'default' => $this->default,
            'card' => $this->card?->jsonSerialize(),
            'meta' => $this->meta,
        ];
    }
}

