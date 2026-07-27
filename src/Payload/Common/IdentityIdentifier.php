<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

use JsonSerializable;

final readonly class IdentityIdentifier implements JsonSerializable
{
    public function __construct(
        public string $type, // e.g. 'bvn', 'nin', 'ssn', 'tax_id', 'passport', 'national_id'
        public string $value,
        public ?Country $country = null,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'type' => $this->type,
            'value' => $this->value,
            'country' => $this->country?->toString(),
        ];
    }
}
