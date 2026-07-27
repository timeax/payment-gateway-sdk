<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

final readonly class MobileMoneyDestination implements PayoutDestinationPayload
{
    /**
     * @param array<string,mixed> $providerData
     */
    public function __construct(
        public string $phoneNumber,
        public string $operator, // e.g. 'mtn', 'airtel', 'mpesa'
        public ?string $accountName = null,
        public ?Currency $currency = null,
        public ?Country $country = null,
        public array $providerData = [],
    ) {}

    public function method(): PayoutMethod
    {
        return PayoutMethod::mobile_money;
    }

    public function jsonSerialize(): array
    {
        return [
            'method' => $this->method()->value,
            'phoneNumber' => $this->phoneNumber,
            'operator' => $this->operator,
            'accountName' => $this->accountName,
            'currency' => $this->currency?->toString(),
            'country' => $this->country?->toString(),
            'providerData' => $this->providerData,
        ];
    }
}
