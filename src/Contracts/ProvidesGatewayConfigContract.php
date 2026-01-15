<?php declare(strict_types=1);

namespace PayKit\Contracts;

use PayKit\Payload\Common\Country;
use PayKit\Payload\Common\Currency;
use PayKit\Payload\Common\GatewayConfig;

interface ProvidesGatewayConfigContract
{
    public function gatewayDriverKey(): string;

    public function gatewayConfig(): GatewayConfig;

    /**
     * @return array<Currency>
     */
    public function getSupportedCurrencies(): array;

    /**
     * @return array<Country>
     */
    public function getSupportedCountries(): array;
}