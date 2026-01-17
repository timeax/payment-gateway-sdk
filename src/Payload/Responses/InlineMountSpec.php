<?php declare(strict_types=1);

namespace PayKit\Payload\Responses;

use JsonSerializable;

final readonly class InlineMountSpec implements JsonSerializable
{
    public function __construct(
        public ?string $containerId = null, // e.g. "paykit-inline-root"
        public ?int $minHeight = null,      // px
        public ?int $height = null,         // px (optional fixed height)
        public bool $fullWidth = true,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'containerId' => $this->containerId,
            'minHeight' => $this->minHeight,
            'height' => $this->height,
            'fullWidth' => $this->fullWidth,
        ];
    }
}