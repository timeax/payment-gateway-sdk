<?php declare(strict_types=1);

namespace PayKit;

use PayKit\Contracts\PaymentGatewayDriverContract;
use PayKit\Contracts\PaymentGatewayPayDriverContract;
use PayKit\Contracts\ProvidesGatewayConfigContract;
use PayKit\Exceptions\GatewayCapabilityException;
use PayKit\Exceptions\GatewayConfigException;
use PayKit\Exceptions\GatewayDriverNotFoundException;
use PayKit\Manager\DriverResolver;
use PayKit\Manager\GatewayManager;
use PayKit\Manager\GatewayRegistry;
use PayKit\Payload\Common\GatewayConfig;

/**
 * PayKit facade / entrypoint.
 *
 * This class provides a **quick**, **static** API for host applications to:
 *
 * - register drivers by `driver_key` (e.g. "stripe", "paystack")
 * - resolve a **config-bound** driver instance for a specific gateway record
 * - keep normal host code concise (one import, one-liners)
 *
 * ### Key idea: Pay = "pay-capable"
 *
 * The facade is intentionally **payments-oriented**:
 * - `Pay::register()` is typed to accept only drivers that implement {@see PaymentGatewayPayDriverContract}
 * - `Pay::via()` returns {@see PaymentGatewayPayDriverContract}, so the caller can immediately call
 *   payments methods (e.g. `initiatePayment`) without extra capability checks.
 *
 * If you need a generic driver (manifest-only, diagnostics-only, scripts-only, etc.),
 * resolve through {@see GatewayManager} directly, or expose a separate "any" facade.
 *
 * ### Static lifecycle notes
 *
 * `Pay` internally keeps static singletons for the registry/manager. This is safe in typical PHP-FPM
 * request lifecycles and is primarily a DX convenience.
 *
 * In long-running processes (Swoole/RoadRunner/queue workers), you should:
 * - call {@see setManager()} or {@see setRegistry()} once at bootstrap time
 * - avoid mutating the registry at runtime unless you intentionally want global changes
 *
 * @final
 */
final class Pay
{
    /**
     * Internal registry singleton.
     *
     * Stores the mapping of `driver_key` => driver class-string.
     *
     * @var GatewayRegistry|null
     */
    private static ?GatewayRegistry $registry = null;

    /**
     * Internal manager singleton.
     *
     * Used to resolve driver instances via {@see DriverResolver} and bind a {@see GatewayConfig}.
     *
     * @var GatewayManager|null
     */
    private static ?GatewayManager $manager = null;

    /**
     * Inject a pre-configured manager instance.
     *
     * Use this when the host application has its own DI container / service provider wiring
     * and wants PayKit to use that manager instance.
     *
     * When a manager is provided, we clear the local registry reference because the manager
     * may be backed by a different registry instance.
     *
     * @param GatewayManager $manager Host-managed manager.
     */
    public static function setManager(GatewayManager $manager): void
    {
        self::$manager = $manager;
        self::$registry = null; // manager may have its own registry
    }

    /**
     * Inject a pre-configured registry instance.
     *
     * Use this when the host application wants to own the driver registrations, possibly
     * building the registry from a plugin system or cache.
     *
     * When a registry is provided, we clear the manager reference so the manager will be rebuilt
     * using this registry on the next {@see manager()} call.
     *
     * @param GatewayRegistry $registry Host-managed registry.
     */
    public static function setRegistry(GatewayRegistry $registry): void
    {
        self::$registry = $registry;
        self::$manager = null; // rebuild manager around this registry
    }

    /**
     * Get the active registry.
     *
     * If the host has not injected a registry via {@see setRegistry()}, PayKit will lazily
     * create a default {@see GatewayRegistry} instance.
     *
     * @return GatewayRegistry
     */
    public static function registry(): GatewayRegistry
    {
        return self::$registry ??= new GatewayRegistry();
    }

    /**
     * Get the active manager.
     *
     * If the host has not injected a manager via {@see setManager()}, PayKit will lazily build one:
     *
     * - {@see DriverResolver} is constructed using {@see registry()}
     * - {@see GatewayManager} is constructed using that resolver
     *
     * @return GatewayManager
     */
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
     * Register a pay-capable driver class for a driver key.
     *
     * This is intended to be called once at bootstrap time (service provider / app init).
     *
     * Example:
     * ```php
     * Pay::register('stripe', \App\Payments\Drivers\StripeDriver::class);
     * ```
     *
     * @param string $driverKey The host-facing key used to identify the driver (persisted in the host DB).
     * @param class-string<PaymentGatewayPayDriverContract> $driverClass The driver class implementing the pay-capable contract bundle.
     */
    public static function register(string $driverKey, string $driverClass): void
    {
        self::registry()->register($driverKey, $driverClass);
    }

    /**
     * Resolve a driver by key and bind a gateway configuration.
     *
     * This returns the base {@see PaymentGatewayDriverContract} because the manager can resolve drivers
     * that may not be pay-capable (manifest-only, scripts-only, etc.) in other host flows.
     *
     * For payment flows, prefer {@see via()} which guarantees {@see PaymentGatewayPayDriverContract}.
     *
     * @param string $driverKey The driver key (e.g. "stripe").
     * @param GatewayConfig $config Gateway configuration (secrets + options) for the specific gateway record.
     * @param bool $validate Whether to validate the config via the driver's config schema before returning the driver.
     *
     * @return PaymentGatewayDriverContract A config-bound driver instance.
     *
     * @throws GatewayDriverNotFoundException If the driver key is not registered.
     * @throws GatewayConfigException If $validate=true and validation fails.
     */
    public static function driver(string $driverKey, GatewayConfig $config, bool $validate = true): PaymentGatewayDriverContract
    {
        return self::manager()->make($driverKey, $config, $validate);
    }

    /**
     * Resolve a pay-capable driver from a host gateway source.
     *
     * The `$source` is typically the host's PaymentGateway model implementing
     * {@see ProvidesGatewayConfigContract}, providing both:
     *
     * - `gatewayDriverKey()` (which driver to use)
     * - `gatewayConfig()` (the config for that gateway record)
     *
     * Returns {@see PaymentGatewayPayDriverContract}, so callers can immediately run payments:
     *
     * ```php
     * $result = Pay::via($gatewayModel)->initiatePayment($request);
     * ```
     *
     * @param ProvidesGatewayConfigContract $source Any object that can supply a driver key and config.
     * @param bool $validate Whether to validate the config before returning the driver.
     *
     * @return PaymentGatewayPayDriverContract A config-bound, pay-capable driver instance.
     *
     * @throws GatewayDriverNotFoundException If the driver key is not registered.
     * @throws GatewayConfigException If $validate=true and validation fails.
     * @throws GatewayCapabilityException If the resolved driver does not implement {@see PaymentGatewayPayDriverContract}.
     */
    public static function via(ProvidesGatewayConfigContract $source, bool $validate = true): PaymentGatewayPayDriverContract
    {
        $driver = self::driver($source->gatewayDriverKey(), $source->gatewayConfig(), $validate);

        // This should never fail if you enforce register() typing,
        // but keep a defensive check for safety.
        if (!$driver instanceof PaymentGatewayPayDriverContract) {
            throw GatewayCapabilityException::notSupported(
                $driver->driverKey(),
                PaymentGatewayPayDriverContract::class,
                'initiatePayment'
            );
        }

        return $driver;
    }
}