<?php declare(strict_types=1);

namespace PayKit\Payload\Requests;

use JsonSerializable;
use PayKit\Payload\Common\Country;
use PayKit\Payload\Common\Currency;
use PayKit\Payload\Common\Metadata;
use PayKit\Payload\Common\Reference;

final readonly class VirtualAccountCreateRequest implements JsonSerializable
{
    public Metadata $meta;

    /**
     * @param array<string,mixed> $context
     */
    public function __construct(
        public Reference $reference,
        public string    $ownerKey,

        public ?Currency $currency = null,
        public ?Country  $country = null,

        public ?string   $customerName = null,
        public ?string   $customerEmail = null,
        public ?string   $customerPhone = null,

        ?Metadata        $meta = null,
        public array     $context = [],
    ) {
        $this->meta = $meta ?? new Metadata([]);
    }

    public function jsonSerialize(): array
    {
        return [
            'reference' => $this->reference->toString(),
            'ownerKey' => $this->ownerKey,
            'currency' => $this->currency?->toString(),
            'country' => $this->country?->toString(),
            'customerName' => $this->customerName,
            'customerEmail' => $this->customerEmail,
            'customerPhone' => $this->customerPhone,
            'meta' => $this->meta->toArray(),
            'context' => $this->context,
        ];
    }
}

