<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

use JsonSerializable;

final readonly class VirtualCardRecord implements JsonSerializable
{
    /**
     * Minimal by design: providers vary widely.
     * @param array<string,mixed> $meta
     */
    public function __construct(
        public string $id,
        public bool   $active = true,
        public array  $meta = [],
    )
    {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'active' => $this->active,
            'meta' => $this->meta,
        ];
    }
}

