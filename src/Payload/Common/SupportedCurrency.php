<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

use JsonSerializable;

final readonly class SupportedCurrency implements JsonSerializable
{
    public function __construct(
        public Currency $currency,
        public bool     $default = false,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'currency' => $this->currency->toString(),
            'default' => $this->default,
        ];
    }
}