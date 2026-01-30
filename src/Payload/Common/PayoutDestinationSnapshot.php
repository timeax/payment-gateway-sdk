<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

use JsonSerializable;

final readonly class PayoutDestinationSnapshot implements JsonSerializable
{
    /**
     * @param array<string,mixed> $meta
     */
    public function __construct(
        public string          $methodKey,
        public string          $identifier,  // acct number / wallet address / phone / email
        public ?string         $accountName = null,
        public PayoutDestination $destination,
        public array           $meta = [],
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'methodKey' => $this->methodKey,
            'identifier' => $this->identifier,
            'accountName' => $this->accountName,
            'destination' => $this->destination,
            'meta' => $this->meta,
        ];
    }
}

