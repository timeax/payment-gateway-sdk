<?php /** @noinspection GrazieInspection */
declare(strict_types=1);

namespace PayKit;

use InvalidArgumentException;
use PayKit\Contracts\EvaluatesGatewayVisibilityContract;
use PayKit\Contracts\PaymentGatewayDriverContract;
use PayKit\Contracts\PaymentGatewayManifestProviderContract;
use PayKit\Contracts\PaymentGatewayPayDriverContract;
use PayKit\Contracts\ProvidesGatewayConfigContract;
use PayKit\Contracts\ProvidesGatewayErrorLogContract;
use PayKit\Contracts\ProvidesGatewayInfoContract;
use PayKit\Exceptions\GatewayCapabilityException;
use PayKit\Exceptions\GatewayConfigException;
use PayKit\Exceptions\GatewayDriverNotFoundException;
use PayKit\Manager\DriverResolver;
use PayKit\Manager\GatewayManager;
use PayKit\Manager\GatewayRegistry;
use PayKit\Payload\Common\Currency;
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

    /** Optional global error logger (host-level). */
    private static ?ProvidesGatewayErrorLogContract $errorLogger = null;

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
     * Optional: set a global logger once (e.g. in a service provider).
     */
    public static function setErrorLogger(?ProvidesGatewayErrorLogContract $logger): void
    {
        self::$errorLogger = $logger;
    }

    /**
     * Register a pay-capable driver, optionally also registering a concrete gateway entry.
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
        self::registry()->register($driverKey, $driverClass, $gatewayId, $providerClass);
    }

    public static function registerGateway(GatewayRegistration $registration): void
    {
        self::registry()->registerGateway($registration);
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
     * @throws GatewayDriverNotFoundException
     * @throws GatewayConfigException
     */
    public static function driver(string $driverKey, GatewayConfig $config, bool $validate = true): PaymentGatewayDriverContract
    {
        return self::manager()->make($driverKey, $config, $validate);
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
     * @return PaymentGatewayPayDriverContract
     * @throws Throwable
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

            try {
                $driver = self::driver($source->gatewayDriverKey(), $source->gatewayConfig(), (bool)$configOrValidate);
                return self::assertPayCapable($driver);
            } catch (Throwable $e) {
                self::reportError('pay.via', $e, [
                    'driverKey' => $source->gatewayDriverKey(),
                    'validate' => (bool)$configOrValidate,
                ], $source);
                throw $e;
            }
        }

        // (B) driverKey + config shortcut
        if ($configOrValidate instanceof GatewayConfig) {
            $driverKey = trim((string)$source);

            if ($driverKey === '') {
                throw new InvalidArgumentException('Driver key cannot be empty.');
            }

            try {
                $driver = self::driver($driverKey, $configOrValidate, $validate);
                return self::assertPayCapable($driver);
            } catch (Throwable $e) {
                self::reportError('pay.via.driverKey', $e, [
                    'driverKey' => $driverKey,
                    'validate' => $validate,
                ]);
                throw $e;
            }
        }

        // (C) gatewayId => resolve provider from registry => resolve driver
        $provider = self::resolveProviderFromGatewayId($source);

        try {
            $driver = self::driver($provider->gatewayDriverKey(), $provider->gatewayConfig(), (bool)$configOrValidate);
            return self::assertPayCapable($driver);
        } catch (Throwable $e) {
            self::reportError('pay.via.gatewayId', $e, [
                'gatewayId' => $source,
                'driverKey' => $provider->gatewayDriverKey(),
                'validate' => (bool)$configOrValidate,
            ], $provider);
            throw $e;
        }
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
            self::reportWarn('pay.via.resolveProvider', "Gateway '$gatewayId' is not registered in the GatewayRegistry.", [
                'gatewayId' => $gatewayId,
            ]);

            throw new InvalidArgumentException(
                "Gateway '$gatewayId' is not registered in the GatewayRegistry."
            );
        }

        $providerClass = $reg->providerClass;

        try {
            /** @var ProvidesGatewayConfigContract $provider */
            $provider = new $providerClass($gatewayId);
            return $provider;
        } catch (Throwable $e) {
            self::reportError('pay.via.resolveProvider', $e, [
                'gatewayId' => $gatewayId,
                'driverKey' => $reg->driverKey,
                'providerClass' => $providerClass,
            ]);

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
            $manifest = self::resolveManifestForList($driverKey, $gatewaysByDriver[$driverKey][0] ?? null, $filter);

            // 2) Driver-level filtering (manifest is priority for generic filtering)
            if ($manifest && !self::manifestPassesFilter($manifest, $filter)) {
                continue;
            }

            $regs = $gatewaysByDriver[$driverKey] ?? [];

            // 3) If there are configured gateways, filter those (provider-first inside here)
            if ($regs) {
                foreach ($regs as $reg) {
                    /** @var class-string<ProvidesGatewayConfigContract> $providerClass */
                    $providerClass = $reg->providerClass;

                    // Instantiate provider (can throw)
                    try {
                        /** @var ProvidesGatewayConfigContract $provider */
                        $provider = new $providerClass($reg->gatewayId);
                    } catch (Throwable $e) {
                        self::reportError('pay.list.provider.instantiate', $e, [
                            'driverKey' => $driverKey,
                            'gatewayId' => $reg->gatewayId,
                            'providerClass' => $providerClass,
                        ]);
                        continue;
                    }

                    // 3a) provider-first hook (can throw)
                    if ($provider instanceof EvaluatesGatewayVisibilityContract) {
                        try {
                            if (!$provider->shouldShow($filter)) {
                                continue;
                            }
                        } catch (Throwable $e) {
                            self::reportError('pay.list.provider.shouldShow', $e, [
                                'driverKey' => $driverKey,
                                'gatewayId' => $reg->gatewayId,
                                'providerClass' => $providerClass,
                                'filter' => self::summarizeFilter($filter),
                            ], $provider);
                            continue;
                        }
                    }

                    // 3b) provider support narrowing (can throw if host does DB work inside)
                    try {
                        if (!self::providerPassesFilter($provider, $filter)) {
                            continue;
                        }
                    } catch (Throwable $e) {
                        self::reportError('pay.list.provider.filter', $e, [
                            'driverKey' => $driverKey,
                            'gatewayId' => $reg->gatewayId,
                            'providerClass' => $providerClass,
                            'filter' => self::summarizeFilter($filter),
                        ], $provider);
                        continue;
                    }

                    // 3c) host-defined info (optional; don’t fail list if this crashes)
                    $info = [];
                    if ($provider instanceof ProvidesGatewayInfoContract) {
                        try {
                            $info = $provider->getInfo($filter);
                        } catch (Throwable $e) {
                            self::reportError('pay.list.provider.info', $e, [
                                'driverKey' => $driverKey,
                                'gatewayId' => $reg->gatewayId,
                                'providerClass' => $providerClass,
                                'filter' => self::summarizeFilter($filter),
                            ], $provider);

                            $info = [];
                        }
                    }

                    $items[] = GatewayListItem::gateway(
                        gatewayId: $reg->gatewayId,
                        driverKey: $driverKey,
                        manifest: $manifest,
                        providerClass: $providerClass,
                        provider: $provider,
                        info: $info,
                        currencies: self::listCurrencies($manifest, $filter),
                    );
                }

                // If gateway instances exist but all were filtered out, do not add driver-only
                continue;
            }

            // 4) No configured gateways: still list driver itself (optional)
            if ($includeDriversWithoutGateways) {
                $items[] = GatewayListItem::driverOnly($driverKey, $manifest, currencies: self::listCurrencies($manifest, $filter));
            }
        }

        return new GatewayListResult($items);
    }

    private static function resolveManifestForList(
        string             $driverKey,
        mixed              $anyGatewayReg = null,
        ?GatewayListFilter $filter = null
    ): ?GatewayManifest
    {
        try {
            // Prefer using an existing gateway config (if we have one), but do not require it.
            if ($anyGatewayReg && isset($anyGatewayReg->providerClass, $anyGatewayReg->gatewayId)) {
                /** @var class-string<ProvidesGatewayConfigContract> $providerClass */
                $providerClass = $anyGatewayReg->providerClass;

                /** @var ProvidesGatewayConfigContract $provider */
                $provider = new $providerClass($anyGatewayReg->gatewayId);

                $config = $provider->gatewayConfig();
            } else {
                $config = new GatewayConfig(options: [], secrets: []);
            }

            $driver = self::manager()->make($driverKey, $config, false);

            if (!$driver instanceof PaymentGatewayManifestProviderContract) {
                self::reportWarn('pay.list.manifest', 'Driver does not implement manifest provider contract.', [
                    'driverKey' => $driverKey,
                ]);
                return null;
            }

            return $driver->getManifest($config);
        } catch (Throwable $e) {
            self::reportError('pay.list.manifest', $e, [
                'driverKey' => $driverKey,
                'filter' => $filter ? self::summarizeFilter($filter) : null,
            ]);
            return null; // don’t kill list() because one manifest failed
        }
    }

    private static function manifestPassesFilter(GatewayManifest $manifest, GatewayListFilter $filter): bool
    {
        // currencies (any-of)
        if ($filter->currencies !== []) {
            $supported = $manifest->supportMatrix->supportedCurrencies ?? [];

            // if driver declares supported currencies, require at least one match
            if ($supported !== [] && !self::containsAnyCode($supported, $filter->currencies)) {
                return false;
            }
        }

        // country (single)
        if ($filter->country) {
            $supported = $manifest->supportMatrix->supportedCountries ?? [];
            if ($supported !== [] && !self::containsCode($supported, $filter->country->code)) {
                return false;
            }
        }

        return !($filter->features && !$filter->features->matches($manifest->features));
    }

    private static function providerPassesFilter(ProvidesGatewayConfigContract $provider, GatewayListFilter $filter): bool
    {
        // currencies (any-of)
        if ($filter->currencies !== []) {
            $curr = $provider->getSupportedCurrencies();

            if ($curr !== [] && !self::containsAnyCode($curr, $filter->currencies)) {
                return false;
            }
        }

        // country (single)
        if ($filter->country) {
            $cty = $provider->getSupportedCountries();

            if ($cty !== [] && !self::containsCode($cty, $filter->country->code)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array<int,mixed> $supported Array of SupportedCurrency|Currency|string-like (whatever you already use)
     * @param array<int,Currency> $wanted
     */
    private static function containsAnyCode(array $supported, array $wanted): bool
    {
        foreach ($wanted as $c) {
            if (self::containsCode($supported, $c->code)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Any currency list returned here MUST exist in the manifest.
     *
     * @return array<int,string> currency codes
     */
    private static function listCurrencies(
        ?GatewayManifest  $manifest,
        GatewayListFilter $filter
    ): array
    {
        $supported = $manifest?->supportMatrix->supportedCurrencies ?? [];
        $supportedCodes = self::uniqueCodes($supported);

        // If manifest doesn't declare currencies, we cannot list any.
        if ($supportedCodes === []) {
            return [];
        }

        // No filter -> show manifest currencies
        if ($filter->currencies === []) {
            return $supportedCodes;
        }

        // Filter -> narrow to intersection (ANY-of)
        $wantedCodes = array_map(
            static fn(Currency $c) => $c->code,
            $filter->currencies
        );

        return array_values(array_intersect($supportedCodes, $wantedCodes));
    }

    /**
     * @param array<int,mixed> $items SupportedCurrency|Currency|string-like
     * @return array<int,string>
     */
    private static function uniqueCodes(array $items): array
    {
        $codes = [];
        foreach ($items as $it) {
            $code = self::codeOf($it);
            if ($code !== null && $code !== '') {
                $codes[$code] = true;
            }
        }
        return array_keys($codes);
    }

    private static function codeOf(mixed $it): ?string
    {
        if ($it instanceof Currency) {
            return $it->code;
        }

        if (is_string($it)) {
            return $it;
        }

        if (is_object($it)) {
            if (property_exists($it, 'code')) {
                return (string)$it->code;
            }
            if (property_exists($it, 'currency') && $it->currency instanceof Currency) {
                return $it->currency->code;
            }
        }

        return null;
    }

    /**
     * @param array<int,mixed> $items
     */
    private static function containsCode(array $items, string $code): bool
    {
        $code = strtoupper($code);

        foreach ($items as $c) {
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

    private static function summarizeFilter(GatewayListFilter $filter): array
    {
        return [
            'currency' => $filter->currency?->code ?? null,
            'country' => $filter->country?->code ?? null,
            'features' => $filter->features ? get_class($filter->features) : null,
        ];
    }

    private static function pickLogger(?ProvidesGatewayConfigContract $provider = null): ?ProvidesGatewayErrorLogContract
    {
        if ($provider instanceof ProvidesGatewayErrorLogContract) {
            return $provider;
        }

        return self::$errorLogger;
    }

    private static function reportError(string $stage, Throwable $error, array $context = [], ?ProvidesGatewayConfigContract $provider = null): void
    {
        $logger = self::pickLogger($provider);
        if (!$logger) return;

        try {
            $logger->reportGatewayError($stage, $error, $context);
        } catch (Throwable) {
            // never let logging break flow
        }
    }

    private static function reportWarn(string $stage, string $message, array $context = [], ?ProvidesGatewayConfigContract $provider = null): void
    {
        $logger = self::pickLogger($provider);
        if (!$logger) return;

        try {
            $logger->reportGatewayWarning($stage, $message, $context);
        } catch (Throwable) {
            // never let logging break flow
        }
    }
}