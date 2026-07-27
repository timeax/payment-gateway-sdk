<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

use JsonSerializable;

final readonly class IdempotencyKey implements JsonSerializable
{
    public function __construct(
        public string $key,
    ) {}

    public function toString(): string
    {
        return $this->key;
    }

    public function jsonSerialize(): string
    {
        return $this->key;
    }
}
