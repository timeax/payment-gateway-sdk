<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

use InvalidArgumentException;
use JsonSerializable;

final class ProviderRef implements JsonSerializable
{
    public function __construct(public readonly string $value)
    {
        $v = trim($value);
        if ($v === '') {
            throw new InvalidArgumentException('ProviderRef cannot be empty.');
        }
    }

    public static function from(string $value): self
    {
        return new self($value);
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}