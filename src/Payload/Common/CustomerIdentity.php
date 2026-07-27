<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

use JsonSerializable;

final readonly class CustomerIdentity implements JsonSerializable
{
    /**
     * @param array<IdentityIdentifier> $identifiers
     * @param array<string,mixed> $address
     */
    public function __construct(
        public ?string $providerCustomerId = null,
        public ?string $name = null,
        public ?string $email = null,
        public ?string $phone = null,
        public ?string $dateOfBirth = null, // YYYY-MM-DD
        public array $address = [],
        public array $identifiers = [],
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'providerCustomerId' => $this->providerCustomerId,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'dateOfBirth' => $this->dateOfBirth,
            'address' => $this->address,
            'identifiers' => array_map(
                static fn (IdentityIdentifier $id) => $id->jsonSerialize(),
                $this->identifiers
            ),
        ];
    }
}
