<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

final readonly class CardDestination implements PayoutDestinationPayload
{
    /**
     * @param array<string,mixed> $providerData
     */
    public function __construct(
        public ?string $cardToken = null,
        public ?CardSummary $cardSummary = null,
        public ?string $accountName = null,
        public ?Currency $currency = null,
        public array $providerData = [],
    ) {}

    public function method(): PayoutMethod
    {
        return PayoutMethod::card;
    }

    public function jsonSerialize(): array
    {
        return [
            'method' => $this->method()->value,
            'cardToken' => $this->cardToken,
            'cardSummary' => $this->cardSummary?->jsonSerialize(),
            'accountName' => $this->accountName,
            'currency' => $this->currency?->toString(),
            'providerData' => $this->providerData,
        ];
    }
}
