<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

use JsonSerializable;

final readonly class PayoutDestination implements JsonSerializable
{
    public function __construct(
        public PayoutDestinationPayload $payload,
        public ?Currency $currency = null,
        public ?Country $country = null,
    ) {}

    public function method(): PayoutMethod
    {
        return $this->payload->method();
    }

    public function jsonSerialize(): array
    {
        return [
            'method' => $this->method()->value,
            'payload' => $this->payload->jsonSerialize(),
            'currency' => $this->currency?->toString(),
            'country' => $this->country?->toString(),
        ];
    }
}
