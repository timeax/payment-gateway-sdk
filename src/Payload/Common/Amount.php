<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

use InvalidArgumentException;
use JsonSerializable;

final readonly class Amount implements JsonSerializable
{
    /** Decimal string (preferred) or integer-like string. Never store floats. */
    public function __construct(public string $value)
    {
        $v = trim($value);
        if ($v === '' || !preg_match('/^-?\d+(\.\d+)?$/', $v)) {
            throw new InvalidArgumentException('Amount must be a numeric string (integer or decimal).');
        }
    }

    public static function from(int|string $value): self
    {
        return new self((string)$value);
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }

    public function toString(): string
    {
        return $this->value;
    }
}