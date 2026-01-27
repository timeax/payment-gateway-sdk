<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

use JsonSerializable;

final readonly class VirtualAccountBank implements JsonSerializable
{
    public function __construct(
        public ?string $name = null,
        public ?string $code = null,
    )
    {
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'code' => $this->code,
        ];
    }
}

