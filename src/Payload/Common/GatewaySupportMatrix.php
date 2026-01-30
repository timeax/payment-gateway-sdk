<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

use JsonSerializable;

final readonly class GatewaySupportMatrix implements JsonSerializable
{
    /**
     * @param array<int,SupportedCurrency> $supportedCurrencies
     * @param array<int,SupportedCountry>  $supportedCountries
     */
    public function __construct(
        public array $supportedCurrencies = [],
        public array $supportedCountries = [],
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'supportedCurrencies' => array_map(
                static fn (SupportedCurrency $c) => $c->jsonSerialize(),
                $this->supportedCurrencies
            ),
            'supportedCountries' => array_map(
                static fn (SupportedCountry $c) => $c->jsonSerialize(),
                $this->supportedCountries
            ),
        ];
    }
}

