<?php declare(strict_types=1);

namespace PayKit\Manager;

use PayKit\Contracts\PaymentGatewayDriverContract;
use PayKit\Exceptions\GatewayDriverNotFoundException;
use PayKit\Payload\Common\GatewayConfig;

final readonly class DriverResolver
{
    public function __construct(private GatewayRegistry $registry)
    {
    }

    public function resolve(string $driverKey, GatewayConfig $config): PaymentGatewayDriverContract
    {
        $class = $this->registry->get($driverKey);

        if (!$class) {
            throw GatewayDriverNotFoundException::for($driverKey, $this->registry->keys());
        }

        /** @var PaymentGatewayDriverContract $driver */
        $driver = new $class($config);

        return $driver;
    }
}