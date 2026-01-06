<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

use InvalidArgumentException;
use JsonSerializable;

final readonly class Country implements JsonSerializable
{
    /** ISO 3166-1 alpha-2-like 2-letter code. */
    public function __construct(public string $code)
    {
        $c = strtoupper(trim($code));
        if (!preg_match('/^[A-Z]{2}$/', $c)) {
            throw new InvalidArgumentException('Country code must be 2 uppercase letters.');
        }
    }

    public static function from(string $code): self
    {
        return new self($code);
    }

    public function jsonSerialize(): string
    {
        return strtoupper($this->code);
    }

    public function toString(): string
    {
        return strtoupper($this->code);
    }

    public function __toString(): string {
        return $this->toString();
    }
}