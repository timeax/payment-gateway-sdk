<?php declare(strict_types=1);

namespace PayKit\Payload\Responses;

use JsonSerializable;
use PayKit\Payload\Common\VirtualAccount;

final readonly class VirtualAccountRecord implements JsonSerializable
{
    /**
     * @param array<string,mixed>|string|null $rawProviderPayload
     */
    public function __construct(
        public VirtualAccount    $virtualAccount,
        public array|string|null $rawProviderPayload = null,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'virtualAccount' => $this->virtualAccount->jsonSerialize(),
            'rawProviderPayload' => $this->rawProviderPayload,
        ];
    }
}



