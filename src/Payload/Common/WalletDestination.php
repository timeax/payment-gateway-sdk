<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

final readonly class WalletDestination implements PayoutDestinationPayload
{
    /**
     * @param array<string,mixed> $providerData
     */
    public function __construct(
        public string $walletId,
        public ?string $walletType = null,
        public ?string $accountName = null,
        public ?Currency $currency = null,
        public array $providerData = [],
    ) {}

    public function method(): PayoutMethod
    {
        return PayoutMethod::wallet;
    }

    public function jsonSerialize(): array
    {
        return [
            'method' => $this->method()->value,
            'walletId' => $this->walletId,
            'walletType' => $this->walletType,
            'accountName' => $this->accountName,
            'currency' => $this->currency?->toString(),
            'providerData' => $this->providerData,
        ];
    }
}
