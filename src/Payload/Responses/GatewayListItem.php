<?php declare(strict_types=1);

namespace PayKit\Payload\Responses;

use JsonSerializable;
use PayKit\Contracts\ProvidesGatewayConfigContract;
use PayKit\Payload\Common\GatewayManifest;

final readonly class GatewayListItem implements JsonSerializable
{
    /**
     * NOTE:
     * - $provider is for *runtime convenience* (host internal use).
     * - It is intentionally NOT serialized.
     */
    public function __construct(
        public string                         $driverKey,
        public int|string|null                $gatewayId = null,
        /** @param array<int,string> $currencies */
        public array                          $currencies = [],
        public ?GatewayManifest               $manifest = null,

        /** @var array<string,mixed> */
        public array                          $info = [],

        /** @var class-string<ProvidesGatewayConfigContract>|null */
        public ?string                        $providerClass = null,

        public ?ProvidesGatewayConfigContract $provider = null,
    )
    {
    }

    public static function driverOnly(string $driverKey, ?GatewayManifest $manifest, array $currencies = []): self
    {
        return new self(driverKey: $driverKey, currencies: $currencies, manifest: $manifest);
    }

    /**
     * @param array<string,mixed> $info
     * @param class-string<ProvidesGatewayConfigContract> $providerClass
     */
    public static function gateway(
        int|string                    $gatewayId,
        string                        $driverKey,
        ?GatewayManifest              $manifest,
        string                        $providerClass,
        ProvidesGatewayConfigContract $provider,
        array                         $info = [],
        array                         $currencies = [],
    ): self
    {
        return new self(
            driverKey: $driverKey,
            gatewayId: $gatewayId,
            currencies: $currencies,
            manifest: $manifest,
            info: $info,
            providerClass: $providerClass,
            provider: $provider,
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'driverKey' => $this->driverKey,
            'gatewayId' => $this->gatewayId,
            'manifest' => $this->manifest,
            'info' => $this->info,
            'currencies' => $this->currencies,
            'providerClass' => $this->providerClass,
        ];
    }
}

