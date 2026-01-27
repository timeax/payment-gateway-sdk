<?php declare(strict_types=1);

namespace PayKit\Payload\Requests;

use JsonSerializable;
use PayKit\Payload\Common\Metadata;

final readonly class AttachMethodRequest implements JsonSerializable
{
    public Metadata $meta;

    /**
     * $token is provider-specific (card token, mandate id, etc.)
     * @param array<string,mixed> $context
     */
    public function __construct(
        public string  $ownerKey,
        public string  $token,
        public ?string $methodType = null,
        public ?string $label = null,
        ?Metadata      $meta = null,
        public array   $context = [],
    ) {
        $this->meta = $meta ?? new Metadata([]);
    }

    public function jsonSerialize(): array
    {
        return [
            'ownerKey' => $this->ownerKey,
            'token' => $this->token,
            'methodType' => $this->methodType,
            'label' => $this->label,
            'meta' => $this->meta->toArray(),
            'context' => $this->context,
        ];
    }
}

