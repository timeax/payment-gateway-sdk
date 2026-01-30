<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

use InvalidArgumentException;
use JsonSerializable;

final readonly class Reference implements JsonSerializable
{
    public function __construct(public string $value)
    {
        $v = trim($value);
        if ($v === '') {
            throw new InvalidArgumentException('Reference cannot be empty.');
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

