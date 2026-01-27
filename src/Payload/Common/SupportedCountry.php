<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

use JsonSerializable;

final readonly class SupportedCountry implements JsonSerializable
{
    public function __construct(
        public Country $country,
        public bool    $default = false,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'country' => $this->country->toString(),
            'default' => $this->default,
        ];
    }
}

