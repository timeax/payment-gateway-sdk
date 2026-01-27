<?php declare(strict_types=1);

namespace PayKit\Payload\Requests;

use JsonSerializable;
use PayKit\Payload\Common\Metadata;
use PayKit\Payload\Common\Money;
use PayKit\Payload\Common\ProviderRef;
use PayKit\Payload\Common\Reference;

final readonly class FrontendConfigRequest implements JsonSerializable
{
    /**
     * Per-session/per-payment computed config request (client secrets, ephemeral keys, etc.).
     * @param array<string,mixed> $context
     */
    public function __construct(
        public Reference    $reference,
        public Money        $money,
        public ?ProviderRef $providerRef = null,
        public Metadata     $meta = new Metadata([]),
        public array        $context = [],
    )
    {
    }

    public function jsonSerialize(): array
    {
        return [
            'reference' => $this->reference->toString(),
            'money' => $this->money->toArray(),
            'providerRef' => $this->providerRef?->toString(),
            'meta' => $this->meta->toArray(),
            'context' => $this->context,
        ];
    }
}

