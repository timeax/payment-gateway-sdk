<?php declare(strict_types=1);

namespace PayKit\Payload\Requests;

use JsonSerializable;
use PayKit\Payload\Common\InboundTransferDecision;
use PayKit\Payload\Common\Money;
use PayKit\Payload\Common\ProviderRef;

final readonly class InboundTransferDecisionRequest implements JsonSerializable
{
    /**
     * @param array<string,mixed> $context
     */
    public function __construct(
        public string $transferId,
        public InboundTransferDecision $decision,
        public ?ProviderRef $providerRef = null,
        public ?string $reason = null,
        public ?Money $approvedAmount = null,
        public array $context = [],
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'transferId' => $this->transferId,
            'decision' => $this->decision->value,
            'providerRef' => $this->providerRef?->toString(),
            'reason' => $this->reason,
            'approvedAmount' => $this->approvedAmount?->toArray(),
            'context' => $this->context,
        ];
    }
}
