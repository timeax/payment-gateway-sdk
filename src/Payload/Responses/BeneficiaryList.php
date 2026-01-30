<?php declare(strict_types=1);

namespace PayKit\Payload\Responses;

use JsonSerializable;
use PayKit\Payload\Common\Beneficiary;

final readonly class BeneficiaryList implements JsonSerializable
{
    /** @param array<int,Beneficiary> $items */
    public function __construct(public array $items = [])
    {
    }

    public function jsonSerialize(): array
    {
        return [
            'items' => array_map(static fn (Beneficiary $b) => $b->jsonSerialize(), $this->items),
        ];
    }
}



