<?php declare(strict_types=1);

namespace PayKit\Payload\Requests;

use JsonSerializable;
use PayKit\Payload\Common\Metadata;
use PayKit\Payload\Common\Money;
use PayKit\Payload\Common\ProviderRef;
use PayKit\Payload\Common\Reference;

final readonly class RefundRequest implements JsonSerializable
{
    public Metadata $meta;

    /**
     * @param array<string,mixed> $context
     */
    public function __construct(
        public Reference    $reference,           // host refund reference
        public Money        $money,                   // amount to refund
        public ?Reference   $paymentReference = null,
        public ?ProviderRef $paymentProviderRef = null,

        ?Metadata           $meta = null,
        public array        $context = [],
    ) {
        $this->meta = $meta ?? new Metadata([]);
    }

    public function jsonSerialize(): array
    {
        return [
            'reference' => $this->reference->toString(),
            'money' => $this->money->toArray(),
            'paymentReference' => $this->paymentReference?->toString(),
            'paymentProviderRef' => $this->paymentProviderRef?->toString(),
            'meta' => $this->meta->toArray(),
            'context' => $this->context,
        ];
    }
}

