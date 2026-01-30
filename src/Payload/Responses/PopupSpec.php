<?php declare(strict_types=1);

namespace PayKit\Payload\Responses;

use JsonSerializable;

final readonly class PopupSpec implements JsonSerializable
{
    public function __construct(
        public string  $variant = 'modal',     // modal|drawer|sheet
        public ?string $size = 'md',          // sm|md|lg|xl (host interprets)
        public bool    $dismissible = true,
        public bool    $closeOnBackdrop = true,
        public ?string $placement = null,     // e.g. "right" for drawer
    )
    {
    }

    public function jsonSerialize(): array
    {
        return [
            'variant' => $this->variant,
            'size' => $this->size,
            'dismissible' => $this->dismissible,
            'closeOnBackdrop' => $this->closeOnBackdrop,
            'placement' => $this->placement,
        ];
    }
}

