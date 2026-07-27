<?php declare(strict_types=1);

namespace PayKit\Payload\Requests;

use JsonSerializable;
use PayKit\Payload\Common\Country;
use PayKit\Payload\Common\Currency;
use PayKit\Payload\Common\CustomerIdentity;
use PayKit\Payload\Common\Metadata;
use PayKit\Payload\Common\Money;
use PayKit\Payload\Common\Reference;
use PayKit\Payload\Common\VirtualAccountPurpose;
use PayKit\Payload\Common\VirtualAccountUsage;

final readonly class VirtualAccountCreateRequest implements JsonSerializable
{
    public Metadata $meta;

    /**
     * @param array<string,mixed> $context
     */
    public function __construct(
        public Reference $reference,
        public string $ownerKey,
        public VirtualAccountPurpose $purpose = VirtualAccountPurpose::collection,
        public VirtualAccountUsage $usage = VirtualAccountUsage::reusable,
        public ?Currency $currency = null,
        public ?Country $country = null,
        public ?CustomerIdentity $customer = null,
        public ?Money $expectedAmount = null,
        public ?string $expiresAt = null, // ISO string
        ?Metadata $meta = null,
        public array $context = [],
    ) {
        $this->meta = $meta ?? new Metadata([]);
    }

    public function jsonSerialize(): array
    {
        return [
            'reference' => $this->reference->toString(),
            'ownerKey' => $this->ownerKey,
            'purpose' => $this->purpose->value,
            'usage' => $this->usage->value,
            'currency' => $this->currency?->toString(),
            'country' => $this->country?->toString(),
            'customer' => $this->customer?->jsonSerialize(),
            'expectedAmount' => $this->expectedAmount?->toArray(),
            'expiresAt' => $this->expiresAt,
            'meta' => $this->meta->toArray(),
            'context' => $this->context,
        ];
    }
}
