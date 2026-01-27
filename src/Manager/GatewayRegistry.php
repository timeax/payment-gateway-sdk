<?php declare(strict_types=1);

namespace PayKit\Manager;

use InvalidArgumentException;
use PayKit\Contracts\ProvidesGatewayConfigContract;
use PayKit\Payload\Common\GatewayRegistration;

final class GatewayRegistry
{
    /** @var array<string, class-string> */
    private array $drivers = [];

    /** @var array<string, GatewayRegistration> */
    private array $gateways = [];

    /** @var class-string<ProvidesGatewayConfigContract>|null */
    private ?string $defaultProviderClass = null;

    /**
     * Set a default provider class used when registering gateways without passing providerClass.
     *
     * @param class-string<ProvidesGatewayConfigContract> $providerClass
     */
    public function setProviderClass(string $providerClass): void
    {
        $providerClass = trim($providerClass);

        if ($providerClass === '') {
            throw new InvalidArgumentException('Provider class cannot be empty.');
        }

        if (!class_exists($providerClass)) {
            throw new InvalidArgumentException("Provider class '$providerClass' does not exist.");
        }

        if (!is_subclass_of($providerClass, ProvidesGatewayConfigContract::class)) {
            throw new InvalidArgumentException(
                "Provider class '$providerClass' must implement " . ProvidesGatewayConfigContract::class
            );
        }

        $this->defaultProviderClass = $providerClass;
    }

    /** @return class-string<ProvidesGatewayConfigContract>|null */
    public function providerClass(): ?string
    {
        return $this->defaultProviderClass;
    }

    /**
     * @param string $driverKey
     * @param class-string $driverClass
     * @param int|string|null $gatewayId
     * @param class-string<ProvidesGatewayConfigContract>|null $providerClass
     */
    public function register(
        string          $driverKey,
        string          $driverClass,
        int|string|null $gatewayId = null,
        ?string         $providerClass = null,
    ): void
    {
        $driverKey = trim($driverKey);

        if ($driverKey === '') {
            throw new InvalidArgumentException('Driver key cannot be empty.');
        }

        if (trim($driverClass) === '') {
            throw new InvalidArgumentException('Driver class cannot be empty.');
        }

        $this->drivers[$driverKey] = $driverClass;

        if ($gatewayId !== null) {
            $providerClass = $providerClass ?: $this->defaultProviderClass;

            if (!$providerClass) {
                throw new InvalidArgumentException(
                    "Provider class must be specified when registering a gatewayId, or set a default via setProviderClass()."
                );
            }

            $this->registerGateway(new GatewayRegistration(
                gatewayId: $gatewayId,
                driverKey: $driverKey,
                providerClass: $providerClass,
            ));
        }
    }

    public function registerGateway(GatewayRegistration $registration): void
    {
        $driverKey = trim($registration->driverKey);

        if ($driverKey === '') {
            throw new InvalidArgumentException('GatewayRegistration.driverKey cannot be empty.');
        }

        if (!isset($this->drivers[$driverKey])) {
            throw new InvalidArgumentException("Driver '$driverKey' is not registered.");
        }

        $k = $this->normalizeGatewayId($registration->gatewayId);
        $this->gateways[$k] = $registration;
    }

    /** @return class-string|null */
    public function get(string $driverKey): ?string
    {
        return $this->drivers[$driverKey] ?? null;
    }

    public function has(string $driverKey): bool
    {
        return array_key_exists($driverKey, $this->drivers);
    }

    /** @return array<string, class-string> */
    public function all(): array
    {
        return $this->drivers;
    }

    /** @return array<int, GatewayRegistration> */
    public function gateways(): array
    {
        return array_values($this->gateways);
    }

    /** @return array<string, GatewayRegistration> */
    public function gatewaysMap(): array
    {
        return $this->gateways;
    }

    public function getGateway(int|string $gatewayId): ?GatewayRegistration
    {
        return $this->gateways[$this->normalizeGatewayId($gatewayId)] ?? null;
    }

    public function hasGateway(int|string $gatewayId): bool
    {
        return isset($this->gateways[$this->normalizeGatewayId($gatewayId)]);
    }

    /** @return array<int, string> */
    public function keys(): array
    {
        return array_keys($this->drivers);
    }

    private function normalizeGatewayId(int|string $id): string
    {
        return is_int($id) ? 'i:' . $id : 's:' . $id;
    }
}

