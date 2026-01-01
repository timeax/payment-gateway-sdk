<?php declare(strict_types=1);

namespace PayKit\Payload\Requests;

use JsonSerializable;
use PayKit\Payload\Common\Metadata;

final readonly class VirtualAccountAssignRequest implements JsonSerializable
{
    public Metadata $meta;

    /**
     * Assign/link an existing VA to an owner in the host domain.
     * @param array<string,mixed> $context
     */
    public function __construct(
        public string $virtualAccountId,
        public string $ownerKey,
        ?Metadata     $meta = null,
        public array  $context = [],
    ) {
        $this->meta = $meta ?? new Metadata([]);
    }

    public function jsonSerialize(): array
    {
        return [
            'virtualAccountId' => $this->virtualAccountId,
            'ownerKey' => $this->ownerKey,
            'meta' => $this->meta->toArray(),
            'context' => $this->context,
        ];
    }
}