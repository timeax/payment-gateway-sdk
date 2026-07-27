<?php declare(strict_types=1);

namespace PayKit\Payload\Requests;

use JsonSerializable;
use PayKit\Payload\Common\IdempotencyKey;
use PayKit\Payload\Common\Metadata;
use PayKit\Payload\Common\Money;
use PayKit\Payload\Common\Reference;

final readonly class TransferRequest implements JsonSerializable
{
    public Metadata $meta;

    /**
     * @param array<string,mixed> $context
     */
    public function __construct(
        public Reference $reference,
        public string $sourceAccountId,
        public string $destinationAccountId,
        public Money $money,
        public ?IdempotencyKey $idempotencyKey = null,
        public ?string $narration = null,
        ?Metadata $meta = null,
        public array $context = [],
    ) {
        $this->meta = $meta ?? new Metadata([]);
    }

    public function jsonSerialize(): array
    {
        return [
            'reference' => $this->reference->toString(),
            'sourceAccountId' => $this->sourceAccountId,
            'destinationAccountId' => $this->destinationAccountId,
            'money' => $this->money->toArray(),
            'idempotencyKey' => $this->idempotencyKey?->toString(),
            'narration' => $this->narration,
            'meta' => $this->meta->toArray(),
            'context' => $this->context,
        ];
    }
}
