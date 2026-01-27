<?php declare(strict_types=1);

namespace PayKit\Payload\Requests;

use JsonSerializable;
use PayKit\Payload\Common\Country;
use PayKit\Payload\Common\Metadata;
use PayKit\Payload\Common\Money;
use PayKit\Payload\Common\Reference;

final readonly class PaymentInitiateRequest implements JsonSerializable
{
    /**
     * @param array<string,mixed> $context Host-provided extra context (opaque to SDK; drivers may use it).
     */
    public function __construct(
        public Reference $reference,
        public Money     $money,
        public ?Country  $country = null,

        public ?string   $customerEmail = null,
        public ?string   $customerName = null,
        public ?string   $customerPhone = null,

        public ?string   $returnUrl = null,
        public ?string   $cancelUrl = null,

        public Metadata  $meta = new Metadata([]),

        public array     $context = [],
    )
    {
    }

    public function jsonSerialize(): array
    {
        return [
            'reference' => $this->reference->toString(),
            'money' => $this->money->toArray(),
            'country' => $this->country?->toString(),

            'customerEmail' => $this->customerEmail,
            'customerName' => $this->customerName,
            'customerPhone' => $this->customerPhone,

            'returnUrl' => $this->returnUrl,
            'cancelUrl' => $this->cancelUrl,

            'meta' => $this->meta->toArray(),
            'context' => $this->context,
        ];
    }
}

