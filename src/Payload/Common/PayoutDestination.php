<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

use JsonSerializable;

final readonly class PayoutDestination implements JsonSerializable
{
    /**
     * @param array<string,mixed> $details provider-specific destination fields (opaque to SDK)
     */
    public function __construct(
        public PayoutMethod $method,
        public array        $details = [],
        public ?Currency    $currency = null,
        public ?Country     $country = null,
    )
    {
    }

    public function jsonSerialize(): array
    {
        return [
            'method' => $this->method->value,
            'details' => $this->details,
            'currency' => $this->currency?->toString(),
            'country' => $this->country?->toString(),
        ];
    }
}