<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

use InvalidArgumentException;
use JsonSerializable;

final readonly class Currency implements JsonSerializable
{
    /** ISO 4217-like 3-letter code (SDK allows any 3 letters; host can enforce its own whitelist). */
    public function __construct(public string $code)
    {
        $c = strtoupper(trim($code));
        if (!preg_match('/^[A-Z]{3}$/', $c)) {
            throw new InvalidArgumentException('Currency code must be 3 uppercase letters.');
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

    public function __toString()
    {
        return $this->toString();
    }
}

