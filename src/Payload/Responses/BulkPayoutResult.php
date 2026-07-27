<?php declare(strict_types=1);

namespace PayKit\Payload\Responses;

use JsonSerializable;
use PayKit\Payload\Common\CanonicalPayoutStatus;
use PayKit\Payload\Common\GatewayFailure;
use PayKit\Payload\Common\ProviderRef;
use PayKit\Payload\Common\Reference;

final readonly class BulkPayoutResult implements JsonSerializable
{
    /**
     * @param array<PayoutResult> $itemResults
     * @param array<string,mixed>|string|null $rawProviderPayload
     */
    public function __construct(
        public Reference $reference,
        public ?ProviderRef $providerRef,
        public CanonicalPayoutStatus $status,
        public int $totalCount = 0,
        public int $successCount = 0,
        public int $failedCount = 0,
        public array $itemResults = [],
        public ?GatewayFailure $failure = null,
        public array|string|null $rawProviderPayload = null,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'reference' => $this->reference->toString(),
            'providerRef' => $this->providerRef?->toString(),
            'status' => $this->status->value,
            'totalCount' => $this->totalCount,
            'successCount' => $this->successCount,
            'failedCount' => $this->failedCount,
            'itemResults' => array_map(
                static fn (PayoutResult $r) => $r->jsonSerialize(),
                $this->itemResults
            ),
            'failure' => $this->failure?->jsonSerialize(),
            'rawProviderPayload' => $this->rawProviderPayload,
        ];
    }
}
