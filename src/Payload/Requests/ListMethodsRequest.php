<?php declare(strict_types=1);

namespace PayKit\Payload\Requests;

use JsonSerializable;
use PayKit\Payload\Common\Metadata;

final readonly class ListMethodsRequest implements JsonSerializable
{
    public Metadata $meta;

    /**
     * Owner is host-defined (user, account, org, etc.).
     * @param array<string,mixed> $context
     */
    public function __construct(
        public string  $ownerKey,
        public ?string $methodType = null, // "card"|"bank"|...
        ?Metadata      $meta = null,
        public array   $context = [],
    )
    {
        $this->meta = $meta ?? new Metadata([]);
    }

    public function jsonSerialize(): array
    {
        return [
            'ownerKey' => $this->ownerKey,
            'methodType' => $this->methodType,
            'meta' => $this->meta->toArray(),
            'context' => $this->context,
        ];
    }
}

