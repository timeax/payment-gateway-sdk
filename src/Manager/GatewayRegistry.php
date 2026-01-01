<?php declare(strict_types=1);

namespace PayKit\Manager;

use PayKit\Contracts\PaymentGatewayDriverContract;

final class GatewayRegistry
{
    /** @var array<string, class-string<PaymentGatewayDriverContract>> */
    private array $drivers = [];

    /**
     * @param class-string<PaymentGatewayDriverContract> $driverClass
     */
    public function register(string $driverKey, string $driverClass): void
    {
        $this->drivers[$driverKey] = $driverClass;
    }

    public function has(string $driverKey): bool
    {
        return isset($this->drivers[$driverKey]);
    }

    /**
     * @return class-string<PaymentGatewayDriverContract>|null
     */
    public function get(string $driverKey): ?string
    {
        return $this->drivers[$driverKey] ?? null;
    }

    /** @return array<int,string> */
    public function keys(): array
    {
        return array_keys($this->drivers);
    }

    /** @return array<string,class-string<PaymentGatewayDriverContract>> */
    public function all(): array
    {
        return $this->drivers;
    }
}