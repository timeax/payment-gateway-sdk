<?php declare(strict_types=1);

namespace PayKit\Drivers;

use PayKit\Contracts\EvaluatesGatewayVisibilityContract;
use PayKit\Contracts\ProvidesGatewayConfigContract;
use PayKit\Payload\Requests\GatewayListFilter;
use PayKit\Payload\Common\GatewayConfig;

abstract readonly class AbstractPaymentGatewayRegistration implements
    ProvidesGatewayConfigContract,
    EvaluatesGatewayVisibilityContract
{
    final public function __construct(protected int|string $gatewayId)
    {
    }

    final public function gatewayId(): int|string
    {
        return $this->gatewayId;
    }

    abstract public function gatewayDriverKey(): string;

    abstract public function gatewayConfig(): GatewayConfig;

    abstract public function getSupportedCurrencies(): array;

    abstract public function getSupportedCountries(): array;

    public function shouldShow(GatewayListFilter $filter): bool
    {
        return true;
    }
}