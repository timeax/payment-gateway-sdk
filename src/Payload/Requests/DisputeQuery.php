<?php declare(strict_types=1);

namespace PayKit\Payload\Requests;

use JsonSerializable;
use PayKit\Payload\Common\Metadata;
use PayKit\Payload\Common\ProviderRef;
use PayKit\Payload\Common\Reference;

final readonly class DisputeQuery implements JsonSerializable
{
    public Metadata $meta;

    /**
     * @param array<string,mixed> $context
     */
    public function __construct(
        public ?ProviderRef $providerRef = null, // dispute id on provider
        public ?Reference   $reference = null,     // host reference (optional)
        ?Metadata           $meta = null,
        public array        $context = [],
    ) {
        $this->meta = $meta ?? new Metadata([]);
    }

    public function jsonSerialize(): array
    {
        return [
            'providerRef' => $this->providerRef?->toString(),
            'reference' => $this->reference?->toString(),
            'meta' => $this->meta->toArray(),
            'context' => $this->context,
        ];
    }
}

