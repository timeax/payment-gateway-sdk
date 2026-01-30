<?php declare(strict_types=1);

namespace PayKit\Drivers\Concerns;

use PayKit\Exceptions\GatewayRuntimeException;
use Timeax\ConfigSchema\Support\ConfigBag;

trait ResolvesConfig
{
    protected ?ConfigBag $defaultConfig = null;

    protected function setDefaultConfig(?ConfigBag $config): void
    {
        $this->defaultConfig = $config;
    }

    protected function resolveConfig(?ConfigBag $override = null): ConfigBag
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


