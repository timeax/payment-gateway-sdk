<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

final readonly class CryptoDestination implements PayoutDestinationPayload
{
    /**
     * @param array<string,mixed> $providerData
     */
    public function __construct(
        public string $address,
        public ?string $network = null,
        public ?string $asset = null,
        public array $providerData = [],
    ) {}

    public function method(): PayoutMethod
    {
        return PayoutMethod::crypto;
    }

    public function jsonSerialize(): array
    {
        return [
            'method' => $this->method()->value,
            'address' => $this->address,
            'network' => $this->network,
            'asset' => $this->asset,
            'providerData' => $this->providerData,
        ];
    }
}
