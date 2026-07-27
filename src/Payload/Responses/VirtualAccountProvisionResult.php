<?php declare(strict_types=1);

namespace PayKit\Payload\Responses;

use Elqora\Interactions\Contracts\Interaction;
use JsonSerializable;
use PayKit\Payload\Common\GatewayFailure;
use PayKit\Payload\Common\VirtualAccount;
use PayKit\Payload\Common\VirtualAccountStatus;

final readonly class VirtualAccountProvisionResult implements JsonSerializable
{
    /**
     * @param array<string,mixed>|string|null $rawProviderPayload
     */
    public function __construct(
        public VirtualAccountStatus $status,
        public ?VirtualAccount $account = null,
        public ?Interaction $interaction = null,
        public ?GatewayFailure $failure = null,
        public array|string|null $rawProviderPayload = null,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'status' => $this->status->value,
            'account' => $this->account?->jsonSerialize(),
            'interaction' => $this->interaction?->toArray(),
            'failure' => $this->failure?->jsonSerialize(),
            'rawProviderPayload' => $this->rawProviderPayload,
        ];
    }
}
