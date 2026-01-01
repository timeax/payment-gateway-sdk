<?php declare(strict_types=1);

namespace PayKit\Payload\Requests;

use JsonSerializable;
use PayKit\Payload\Common\Metadata;

final readonly class GetMethodRequest implements JsonSerializable
{
    public Metadata $meta;

    /**
     * @param array<string,mixed> $context
     */
    public function __construct(
        public string $ownerKey,
        public string $methodId,
        ?Metadata     $meta = null,
        public array  $context = [],
    ) {
        $this->meta = $meta ?? new Metadata([]);
    }

    public function jsonSerialize(): array
    {
        return [
            'ownerKey' => $this->ownerKey,
            'methodId' => $this->methodId,
            'meta' => $this->meta->toArray(),
            'context' => $this->context,
        ];
    }
}