<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

final readonly class BeneficiaryDestination implements PayoutDestinationPayload
{
    public function __construct(
        public string $beneficiaryId,
    ) {}

    public function method(): PayoutMethod
    {
        return PayoutMethod::beneficiary;
    }

    public function jsonSerialize(): array
    {
        return [
            'method' => $this->method()->value,
            'beneficiaryId' => $this->beneficiaryId,
        ];
    }
}
