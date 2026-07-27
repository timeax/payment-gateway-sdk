<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

final readonly class BankAccountDestination implements PayoutDestinationPayload
{
    /**
     * @param array<string,mixed> $providerData
     */
    public function __construct(
        public string $accountNumber,
        public ?string $accountName = null,
        public ?string $bankCode = null,
        public ?string $routingNumber = null,
        public ?string $iban = null,
        public ?string $swiftCode = null,
        public ?Currency $currency = null,
        public ?Country $country = null,
        public array $providerData = [],
    ) {}

    public function method(): PayoutMethod
    {
        return PayoutMethod::bank;
    }

    public function jsonSerialize(): array
    {
        return [
            'method' => $this->method()->value,
            'accountNumber' => $this->accountNumber,
            'accountName' => $this->accountName,
            'bankCode' => $this->bankCode,
            'routingNumber' => $this->routingNumber,
            'iban' => $this->iban,
            'swiftCode' => $this->swiftCode,
            'currency' => $this->currency?->toString(),
            'country' => $this->country?->toString(),
            'providerData' => $this->providerData,
        ];
    }
}
