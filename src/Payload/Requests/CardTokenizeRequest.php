<?php declare(strict_types=1);

namespace PayKit\Payload\Requests;

use JsonSerializable;
use PayKit\Payload\Common\Country;
use PayKit\Payload\Common\Metadata;
use PayKit\Payload\Common\Money;
use PayKit\Payload\Common\Reference;

final readonly class CardTokenizeRequest implements JsonSerializable
{
    public Metadata $meta;

    /**
     * Tokenization may be “initiate a tokenization flow” or “exchange client token”.
     * @param array<string,mixed> $context
     */
    public function __construct(
        public Reference $reference,
        public ?Money    $money = null,
        public ?Country  $country = null,
        public ?string   $returnUrl = null,
        public ?string   $cancelUrl = null,
        ?Metadata        $meta = null,
        public array     $context = [],
    )
    {
        $this->meta = $meta ?? new Metadata([]);
    }

    public function jsonSerialize(): array
    {
        return [
            'reference' => $this->reference->toString(),
            'money' => $this->money?->toArray(),
            'country' => $this->country?->toString(),
            'returnUrl' => $this->returnUrl,
            'cancelUrl' => $this->cancelUrl,
            'meta' => $this->meta->toArray(),
            'context' => $this->context,
        ];
    }
}