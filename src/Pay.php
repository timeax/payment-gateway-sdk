<?php declare(strict_types=1);

namespace PayKit;

use PayKit\Contracts\PaymentGatewayDriverContract;
use PayKit\Contracts\ProvidesGatewayConfigContract;
use PayKit\Manager\DriverResolver;
use PayKit\Manager\GatewayManager;
use PayKit\Manager\GatewayRegistry;
use PayKit\Payload\Common\GatewayConfig;

final class Pay
{
    private static ?GatewayRegistry $registry = null;
    private static ?GatewayManager $manager = null;

    public static function setManager(GatewayManager $manager): void
    {
        self::$manager = $manager;
        self::$registry = null; // manager may have its own registry
    }

    public static function setRegistry(GatewayRegistry $registry): void
    {
        self::$registry = $registry;
        self::$manager = null; // rebuild manager around this registry
    }

    public static function registry(): GatewayRegistry
    {
        return self::$registry ??= new GatewayRegistry();
    }

    public static function manager(): GatewayManager
    {
        // Manager requires a resolver, and resolver requires the registry.
        if (!self::$manager) {
            $resolver = new DriverResolver(self::registry());
            self::$manager = new GatewayManager($resolver);
        }

        return self::$manager;
    }

    /**
     * @param class-string<PaymentGatewayDriverContract> $driverClass
     */
    public static function register(string $driverKey, string $driverClass): void
    {
        self::registry()->register($driverKey, $driverClass);
    }

    public static function driver(string $driverKey, GatewayConfig $config, bool $validate = true): PaymentGatewayDriverContract
    {
        return self::manager()->make($driverKey, $config, $validate);
    }

    public static function via(ProvidesGatewayConfigContract $source, bool $validate = true): PaymentGatewayDriverContract
    {
        return self::driver($source->gatewayDriverKey(), $source->gatewayConfig(), $validate);
    }
}