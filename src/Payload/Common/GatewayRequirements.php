<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

use JsonSerializable;
use Timeax\ConfigSchema\Schema\ConfigField;

final readonly class GatewayRequirements implements JsonSerializable
{
    /**
     * Extra fields the host must collect (e.g., billing address, phone, KYC id).
     * Reuses ConfigField as a generic â€œfield descriptorâ€.
     *
     * @param array<int,ConfigField> $fields
     */
    public function __construct(public array $fields = [])
    {
    }

    public function jsonSerialize(): array
    {
        return [
            'fields' => array_map(static fn (ConfigField $f) => $f->jsonSerialize(), $this->fields),
        ];
    }
}

