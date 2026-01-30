<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

use InvalidArgumentException;
use JsonSerializable;

final readonly class CardFingerprint implements JsonSerializable
{
    public function __construct(public string $value)
    {
        $v = trim($value);
        if ($v === '') {
            throw new InvalidArgumentException('CardFingerprint cannot be empty.');
        }
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }
}

