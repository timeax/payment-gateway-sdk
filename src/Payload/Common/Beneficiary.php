<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

use JsonSerializable;

final readonly class Beneficiary implements JsonSerializable
{
    /**
     * @param array<string,mixed> $meta
     * @param array<string,mixed>|string|null $rawProviderPayload
     */
    public function __construct(
        public string $id,
        public string $name,
        public BeneficiaryStatus $status = BeneficiaryStatus::active,
        public ?PayoutDestinationPayload $destination = null,
        public ?ProviderRef $providerRef = null,
        public array $meta = [],
        public array|string|null $rawProviderPayload = null,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'status' => $this->status->value,
            'destination' => $this->destination?->jsonSerialize(),
            'providerRef' => $this->providerRef?->toString(),
            'meta' => $this->meta,
            'rawProviderPayload' => $this->rawProviderPayload,
        ];
    }
}
