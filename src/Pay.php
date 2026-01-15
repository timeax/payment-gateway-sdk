<?php /** @noinspection GrazieInspection */
declare(strict_types=1);

namespace PayKit;

use InvalidArgumentException;
use PayKit\Contracts\EvaluatesGatewayVisibilityContract;
use PayKit\Contracts\PaymentGatewayDriverContract;
use PayKit\Contracts\PaymentGatewayManifestProviderContract;
use PayKit\Contracts\PaymentGatewayPayDriverContract;
use PayKit\Contracts\ProvidesGatewayConfigContract;
use PayKit\Contracts\ProvidesGatewayInfoContract;
use PayKit\Exceptions\GatewayCapabilityException;
use PayKit\Exceptions\GatewayConfigException;
use PayKit\Exceptions\GatewayDriverNotFoundException;
use PayKit\Manager\DriverResolver;
use PayKit\Manager\GatewayManager;
use PayKit\Manager\GatewayRegistry;
use PayKit\Payload\Common\GatewayConfig;
use PayKit\Payload\Common\GatewayManifest;
use PayKit\Payload\Common\GatewayRegistration;
use PayKit\Payload\Requests\GatewayListFilter;
use PayKit\Payload\Responses\GatewayListItem;
use PayKit\Payload\Responses\GatewayListResult;
use Throwable;

final class Pay
{
    private static ?GatewayRegistry $registry = null;
    private static ?GatewayManager $manager = null;

    public static function setManager(GatewayManager $manager): void
    {
        self::$manager = $manager;
        self::$registry = null;
    }

    public static function setRegistry(GatewayRegistry $registry): void
    {
        self::$registry = $registry;
        self::$manager = null;
    }

    public static function registry(): GatewayRegistry
    {
        return self::$registry ??= new GatewayRegistry();
    }

    public static function manager(): GatewayManager
    {
        if (!self::$manager) {
            $resolver = new DriverResolver(self::registry());
            self::$manager = new GatewayManager($resolver);
        }

        return self::$manager;
    }

    /**
     * Register a pay-capable driver, optionally also registering a concrete gateway entry.
     *
     * Examples:
     *  Pay::register('korapay', KorapayDriver::class);
     *
     *  Pay::register(
     *      'korapay',
     *      KorapayDriver::class,
     *      gatewayId: 12,
     *      providerClass: \App\Payments\Registrations\KoraGatewayRegistration::class
     *  );
     *
     * @param string $driverKey
     * @param class-string<PaymentGatewayPayDriverContract> $driverClass
     * @param int|string|null $gatewayId
     * @param class-string<ProvidesGatewayConfigContract>|null $providerClass
     */
    public static function register(
        string          $driverKey,
        string          $driverClass,
        int|string|null $gatewayId = null,
        ?string         $providerClass = null,
    ): void
    {
        // keep Pay strict: only pay-capable drivers should be registered here
        self::registry()->register($driverKey, $driverClass, $gatewayId, $providerClass);
    }

    /**
     * Convenience: register only a gateway entry (driver must already be registered).
     */
    public static function registerGateway(GatewayRegistration $registration): void
    {
        self::registry()->registerGateway($registration);
    }

    /**
     * @throws GatewayDriverNotFoundException
     * @throws GatewayConfigException
     */
    public static function driver(string $driverKey, GatewayConfig $config, bool $validate = true): PaymentGatewayDriverContract
    {
        return self::manager()->make($driverKey, $config, $validate);
    }

    /**
     * Set the default provider class used for gatewayId registrations.
     *
     * @param class-string<ProvidesGatewayConfigContract> $providerClass
     */
    public static function setProvider(string $providerClass): void
    {
        self::registry()->setProviderClass($providerClass);
    }

    /**
     * Unified resolver for pay-capable drivers.
     *
     * Supported call forms:
     * 1) Provider: Pay::via($provider, true)
     * 2) Gateway ID: Pay::via(12) or Pay::via('gw_abc')
     * 3) Driver key + config: Pay::via('korapay', $config, true)
     *
     * @param ProvidesGatewayConfigContract|int|string $source
     * @param bool|GatewayConfig $configOrValidate
     * @param bool $validate Used only when $configOrValidate is a GatewayConfig
     *
     * @return PaymentGatewayPayDriverContract
     */
    public static function via(
        ProvidesGatewayConfigContract|int|string $source,
        bool|GatewayConfig                       $configOrValidate = true,
        bool                                     $validate = true
    ): PaymentGatewayPayDriverContract
    {
        // (A) Provider instance (BC path)
        if ($source instanceof ProvidesGatewayConfigContract) {
            if ($configOrValidate instanceof GatewayConfig) {
                throw new InvalidArgumentException(
                    'When passing a ProvidesGatewayConfigContract, the second argument must be a boolean validate flag.'
                );
            }

            $driver = self::driver($source->gatewayDriverKey(), $source->gatewayConfig(), (bool)$configOrValidate);
            return self::assertPayCapable($driver);
        }

        // (B) driverKey + config shortcut
        if ($configOrValidate instanceof GatewayConfig) {
            $driverKey = trim((string)$source);

            if ($driverKey === '') {
                throw new InvalidArgumentException('Driver key cannot be empty.');
            }

            $driver = self::driver($driverKey, $configOrValidate, $validate);
            return self::assertPayCapable($driver);
        }

        // (C) gatewayId => resolve provider from registry => resolve driver
        $provider = self::resolveProviderFromGatewayId($source);

        $driver = self::driver($provider->gatewayDriverKey(), $provider->gatewayConfig(), (bool)$configOrValidate);
        return self::assertPayCapable($driver);
    }

    private static function assertPayCapable(PaymentGatewayDriverContract $driver): PaymentGatewayPayDriverContract
    {
        if (!$driver instanceof PaymentGatewayPayDriverContract) {
            throw GatewayCapabilityException::notSupported(
                $driver->driverKey(),
                PaymentGatewayPayDriverContract::class,
                'initiatePayment'
            );
        }

        return $driver;
    }

    private static function resolveProviderFromGatewayId(int|string $gatewayId): ProvidesGatewayConfigContract
    {
        $reg = self::registry()->getGateway($gatewayId);

        if (!$reg) {
            throw new InvalidArgumentException(
                "Gateway '$gatewayId' is not registered in the GatewayRegistry."
            );
        }

        $providerClass = $reg->providerClass;

        try {
            // Convention: providerClass is instantiable with (int|string $gatewayId)
            return new $providerClass($gatewayId);
        } catch (Throwable $e) {
            throw new InvalidArgumentException(
                "Failed to instantiate provider '$providerClass' for gateway '$gatewayId': {$e->getMessage()}",
                previous: $e
            );
        }
    }

    public static function list(?GatewayListFilter $filter = null, bool $includeDriversWithoutGateways = true): GatewayListResult
    {
        $filter ??= new GatewayListFilter();

        $registry = self::registry();

        /** @var array<string, class-string> $drivers */
        $drivers = $registry->all();

        // Group configured gateways by driverKey
        $gatewaysByDriver = [];
        foreach ($registry->gateways() as $reg) {
            $gatewaysByDriver[$reg->driverKey][] = $reg;
        }

        $items = [];

        foreach ($drivers as $driverKey => $_driverClass) {
            // 1) Resolve a manifest (driver-level)
            $manifest = self::resolveManifestForList($driverKey, $gatewaysByDriver[$driverKey][0] ?? null);

            // 2) Driver-level filtering (manifest is priority for generic filtering)
            if ($manifest && !self::manifestPassesFilter($manifest, $filter)) {
                continue;
            }

            $regs = $gatewaysByDriver[$driverKey] ?? [];

            // 3) If there are configured gateways, filter *those* (provider-first inside here)
            if ($regs) {
                foreach ($regs as $reg) {
                    /** @var class-string<ProvidesGatewayConfigContract> $providerClass */
                    $providerClass = $reg->providerClass;

                    // your base class expects (int $gatewayId); keep it consistent
                    /** @var ProvidesGatewayConfigContract $provider */
                    $provider = new $providerClass($reg->gatewayId);

                    // 3a) provider-first hook
                    if (($provider instanceof EvaluatesGatewayVisibilityContract) && !$provider->shouldShow($filter)) {
                        continue;
                    }

                    // 3b) provider support narrowing (optional, only if provider returns non-empty sets)
                    if (!self::providerPassesFilter($provider, $filter)) {
                        continue;
                    }

                    // 3c) host-defined info
                    $info = [];
                    if ($provider instanceof ProvidesGatewayInfoContract) {
                        $info = $provider->getInfo($filter);
                    }

                    $items[] = GatewayListItem::gateway(
                        gatewayId: $reg->gatewayId,
                        driverKey: $driverKey,
                        manifest: $manifest,
                        providerClass: $providerClass,
                        provider: $provider,
                        info: $info,
                    );
                }

                // If gateway instances exist but all were filtered out, we do NOT add driver-only
                // because your host has concrete gateways and provider chose to hide them.
                continue;
            }

            // 4) No configured gateways: still list driver itself (optional)
            if ($includeDriversWithoutGateways) {
                $items[] = GatewayListItem::driverOnly($driverKey, $manifest);
            }
        }

        return new GatewayListResult($items);
    }

    private static function resolveManifestForList(string $driverKey, mixed $anyGatewayReg = null): ?GatewayManifest
    {
        // Prefer using an existing gateway config (if we have one), but do not require it.
        // list() should not be blocked just because there are no gateways saved yet.

        if ($anyGatewayReg && isset($anyGatewayReg->providerClass, $anyGatewayReg->gatewayId)) {
            /** @var class-string<ProvidesGatewayConfigContract> $providerClass */
            $providerClass = $anyGatewayReg->providerClass;
            /** @var ProvidesGatewayConfigContract $provider */
            $provider = new $providerClass($anyGatewayReg->gatewayId);
            $config = $provider->gatewayConfig();
        } else {
            // empty config; drivers that provide manifests should tolerate this
            $config = new GatewayConfig(options: [], secrets: []);
        }

        $driver = self::manager()->make($driverKey, $config, false);

        if (!$driver instanceof PaymentGatewayManifestProviderContract) {
            // If a driver can’t provide a manifest, then list/filter-by-manifest can’t work.
            // You can also throw here if you want it strict.
            return null;
        }

        return $driver->getManifest($config);
    }

    private static function manifestPassesFilter(GatewayManifest $manifest, GatewayListFilter $filter): bool
    {
        // currency
        if ($filter->currency) {
            $supported = $manifest->supportMatrix->supportedCurrencies ?? [];
            if ($supported !== [] && !self::containsCurrency($supported, $filter->currency->code)) {
                return false;
            }
        }

        // country
        if ($filter->country) {
            $supported = $manifest->supportMatrix->supportedCountries ?? [];
            if ($supported !== [] && !self::containsCountry($supported, $filter->country->code)) {
                return false;
            }
        }

        // features
        return !($filter->features && !$filter->features->matches($manifest->features));
    }

    private static function providerPassesFilter(ProvidesGatewayConfigContract $provider, GatewayListFilter $filter): bool
    {
        // If provider returns empty list, we treat it as “no extra restriction”
        if ($filter->currency) {
            $curr = $provider->getSupportedCurrencies();
            if ($curr !== [] && !self::containsCurrency($curr, $filter->currency->code)) {
                return false;
            }
        }

        if ($filter->country) {
            $cty = $provider->getSupportedCountries();
            if ($cty !== [] && !self::containsCountry($cty, $filter->country->code)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array $currencies
     * @param string $code
     * @return bool
     */
    private static function containsCurrency(array $currencies, string $code): bool
    {
        return self::extracted($code, $currencies);
    }

    /**
     * @param array $countries
     * @param string $code
     * @return bool
     */
    private static function containsCountry(array $countries, string $code): bool
    {
        return self::extracted($code, $countries);
    }

    /**
     * @param string $code
     * @param array $countries
     * @return bool
     */
    private static function extracted(string $code, array $countries): bool
    {
        $code = strtoupper($code);

        foreach ($countries as $c) {
            if (is_object($c) && property_exists($c, 'code') && strtoupper((string)$c->code) === $code) {
                return true;
            }
            if (is_array($c) && isset($c['code']) && strtoupper((string)$c['code']) === $code) {
                return true;
            }
            if (is_string($c) && strtoupper($c) === $code) {
                return true;
            }
        }

        return false;
    }
}