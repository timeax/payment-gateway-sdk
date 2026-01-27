<?php
declare(strict_types=1);

namespace PayKit\Payload\Common;

use JsonSerializable;

/**
 * Represents a *configured gateway instance* in the host app (a DB record),
 * not just a driver registration.
 *
 * Example: gateway_id=12 (Korapay Live), gateway_id=13 (Korapay Sandbox)
 * both can share driver_key="korapay".
 */
final readonly class GatewayRegistration implements JsonSerializable
{
    /**
     * @param int|string $gatewayId      Host gateway model primary key (required).
     * @param string     $driverKey      Driver key used to resolve the driver (required).
     * @param string|null $providerClass Host provider class that can fetch config by ID (optional).
     * @param array<string,mixed> $meta  Optional host metadata for listing/UI.
     */
    public function __construct(
        public int|string $gatewayId,
        public string $driverKey,
        public ?string $providerClass = null,
        public array $meta = [],
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'gateway_id' => $this->gatewayId,
            'driver_key' => $this->driverKey,
            'provider'   => $this->providerClass,
            'meta'       => $this->meta,
        ];
    }
}

