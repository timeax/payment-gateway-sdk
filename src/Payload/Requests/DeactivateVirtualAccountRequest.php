<?php declare(strict_types=1);

namespace PayKit\Payload\Requests;

use JsonSerializable;
use PayKit\Payload\Common\Metadata;

final readonly class DeactivateVirtualAccountRequest implements JsonSerializable
{
    public Metadata $meta;

    /**
     * @param array<string,mixed> $context
     */
    public function __construct(
        public string  $virtualAccountId,
        public ?string $reason = null,
        ?Metadata      $meta = null,
        public array   $context = [],
    ) {
        $this->meta = $meta ?? new Metadata([]);
    }

    public function jsonSerialize(): array
    {
        return [
            'virtualAccountId' => $this->virtualAccountId,
            'reason' => $this->reason,
            'meta' => $this->meta->toArray(),
            'context' => $this->context,
        ];
    }
}