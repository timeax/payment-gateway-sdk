<?php declare(strict_types=1);

namespace PayKit\Payload\Requests;

use JsonSerializable;
use PayKit\Payload\Common\ProviderRef;
use PayKit\Payload\Common\Reference;

final readonly class BulkPayoutVerifyRequest implements JsonSerializable
{
    public function __construct(
        public Reference $reference,
        public ?ProviderRef $providerRef = null,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'reference' => $this->reference->toString(),
            'providerRef' => $this->providerRef?->toString(),
        ];
    }
}
