<?php declare(strict_types=1);

namespace PayKit\Payload\Requests;

use JsonSerializable;
use PayKit\Payload\Common\PayoutDestinationPayload;

final readonly class PayoutDestinationResolveRequest implements JsonSerializable
{
    /**
     * @param array<string,mixed> $context
     */
    public function __construct(
        public PayoutDestinationPayload $destination,
        public array $context = [],
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'destination' => $this->destination->jsonSerialize(),
            'context' => $this->context,
        ];
    }
}
