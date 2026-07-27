<?php declare(strict_types=1);

namespace PayKit\Payload\Requests;

use JsonSerializable;
use PayKit\Payload\Common\CardControls;
use PayKit\Payload\Common\Currency;
use PayKit\Payload\Common\CustomerIdentity;
use PayKit\Payload\Common\IdempotencyKey;
use PayKit\Payload\Common\Metadata;
use PayKit\Payload\Common\Reference;

final readonly class VirtualCardCreateRequest implements JsonSerializable
{
    public Metadata $meta;

    /**
     * @param array<string,mixed> $context
     */
    public function __construct(
        public Reference $reference,
        public string $cardholderName,
        public Currency $currency,
        public ?CustomerIdentity $cardholderIdentity = null,
        public ?string $balanceAccountId = null,
        public ?CardControls $controls = null,
        public ?IdempotencyKey $idempotencyKey = null,
        ?Metadata $meta = null,
        public array $context = [],
    ) {
        $this->meta = $meta ?? new Metadata([]);
    }

    public function jsonSerialize(): array
    {
        return [
            'reference' => $this->reference->toString(),
            'cardholderName' => $this->cardholderName,
            'currency' => $this->currency->toString(),
            'cardholderIdentity' => $this->cardholderIdentity?->jsonSerialize(),
            'balanceAccountId' => $this->balanceAccountId,
            'controls' => $this->controls?->jsonSerialize(),
            'idempotencyKey' => $this->idempotencyKey?->toString(),
            'meta' => $this->meta->toArray(),
            'context' => $this->context,
        ];
    }
}
