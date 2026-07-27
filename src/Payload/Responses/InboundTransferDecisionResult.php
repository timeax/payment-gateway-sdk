<?php declare(strict_types=1);

namespace PayKit\Payload\Responses;

use JsonSerializable;
use PayKit\Payload\Common\GatewayFailure;

final readonly class InboundTransferDecisionResult implements JsonSerializable
{
    /**
     * @param array<string,mixed>|string|null $rawProviderPayload
     */
    public function __construct(
        public bool $success,
        public string $status, // 'approved', 'declined', 'failed'
        public ?GatewayFailure $failure = null,
        public array|string|null $rawProviderPayload = null,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'success' => $this->success,
            'status' => $this->status,
            'failure' => $this->failure?->jsonSerialize(),
            'rawProviderPayload' => $this->rawProviderPayload,
        ];
    }
}
