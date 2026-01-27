<?php declare(strict_types=1);

namespace PayKit\Payload\Requests;

use JsonSerializable;
use PayKit\Payload\Common\Metadata;
use PayKit\Payload\Common\Money;
use PayKit\Payload\Common\PayoutDestination;
use PayKit\Payload\Common\Reference;

final readonly class VirtualAccountWithdrawalRequest implements JsonSerializable
{
    public Metadata $meta;

    /**
     * @param array<string,mixed> $context
     */
    public function __construct(
        public Reference          $reference,
        public string             $virtualAccountId,
        public Money              $money,
        public ?PayoutDestination $destination = null,
        ?Metadata                 $meta = null,
        public array              $context = [],
    )
    {
        $this->meta = $meta ?? new Metadata([]);
    }

    public function jsonSerialize(): array
    {
        return [
            'reference' => $this->reference->toString(),
            'virtualAccountId' => $this->virtualAccountId,
            'money' => $this->money->toArray(),
            'destination' => $this->destination?->jsonSerialize(),
            'meta' => $this->meta->toArray(),
            'context' => $this->context,
        ];
    }
}

