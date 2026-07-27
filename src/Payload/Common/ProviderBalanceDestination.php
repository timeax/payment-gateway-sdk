<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

final readonly class ProviderBalanceDestination implements PayoutDestinationPayload
{
    public function __construct(
        public string $balanceAccountId,
        public ?Currency $currency = null,
    ) {}

    public function method(): PayoutMethod
    {
        return PayoutMethod::provider_balance;
    }

    public function jsonSerialize(): array
    {
        return [
            'method' => $this->method()->value,
            'balanceAccountId' => $this->balanceAccountId,
            'currency' => $this->currency?->toString(),
        ];
    }
}
