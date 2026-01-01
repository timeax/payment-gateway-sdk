<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

use JsonSerializable;

final readonly class VirtualAccount implements JsonSerializable
{
    /**
     * @param array<string,mixed> $meta
     */
    public function __construct(
        public string              $id, // host or provider stable id
        public ?string             $accountName = null,
        public ?string             $accountNumber = null,
        public ?VirtualAccountBank $bank = null,
        public ?Currency           $currency = null,
        public ?Country            $country = null,
        public bool                $active = true,
        public array               $meta = [],
    )
    {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'accountName' => $this->accountName,
            'accountNumber' => $this->accountNumber,
            'bank' => $this->bank?->jsonSerialize(),
            'currency' => $this->currency?->toString(),
            'country' => $this->country?->toString(),
            'active' => $this->active,
            'meta' => $this->meta,
        ];
    }
}