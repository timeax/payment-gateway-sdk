<?php declare(strict_types=1);

namespace PayKit\Payload\Requests;

use JsonSerializable;
use PayKit\Payload\Common\Metadata;
use PayKit\Payload\Common\ProviderRef;
use PayKit\Payload\Common\Reference;

final readonly class PayoutVerifyRequest implements JsonSerializable
{
    public Metadata $meta;

    /**
     * @param array<string,mixed> $context
     */
    public function __construct(
        public ?Reference   $reference = null,
        public ?ProviderRef $providerRef = null,
        ?Metadata           $meta = null,
        public array        $context = [],
    )
    {
        $this->meta = $meta ?? new Metadata([]);
    }

    public function jsonSerialize(): array
    {
        return [
            'reference' => $this->reference?->toString(),
            'providerRef' => $this->providerRef?->toString(),
            'meta' => $this->meta->toArray(),
            'context' => $this->context,
        ];
    }
}

