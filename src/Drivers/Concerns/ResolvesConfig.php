<?php declare(strict_types=1);

namespace PayKit\Drivers\Concerns;

use PayKit\Exceptions\GatewayRuntimeException;
use PayKit\Payload\Common\GatewayConfig;

trait ResolvesConfig
{
    protected ?GatewayConfig $defaultConfig = null;

    protected function setDefaultConfig(?GatewayConfig $config): void
    {
        $this->defaultConfig = $config;
    }

    protected function resolveConfig(?GatewayConfig $override = null): GatewayConfig
    {
        $config = $override ?? $this->defaultConfig;

        if (!$config) {
            $ctx = [];
            if (method_exists($this, 'driverKey')) {
                $ctx['driverKey'] = $this->driverKey();
            }

            throw new GatewayRuntimeException(
                'Gateway configuration missing. Pass it to the constructor (manager) or provide it to the method call.',
                $ctx
            );
        }

        return $config;
    }
}