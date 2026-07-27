<?php declare(strict_types=1);

namespace PayKit\Payload\Responses;

use JsonSerializable;
use PayKit\Payload\Common\Beneficiary;
use PayKit\Payload\Common\BeneficiaryStatus;
use PayKit\Payload\Common\GatewayFailure;

final readonly class BeneficiaryCreateResult implements JsonSerializable
{
    /**
     * @param array<string,mixed>|string|null $rawProviderPayload
     */
    public function __construct(
        public BeneficiaryStatus $status,
        public ?Beneficiary $beneficiary = null,
        public ?GatewayFailure $failure = null,
        public array|string|null $rawProviderPayload = null,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'status' => $this->status->value,
            'beneficiary' => $this->beneficiary?->jsonSerialize(),
            'failure' => $this->failure?->jsonSerialize(),
            'rawProviderPayload' => $this->rawProviderPayload,
        ];
    }
}
