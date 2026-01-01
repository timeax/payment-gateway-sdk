<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

use JsonSerializable;

final readonly class GatewayConfigSchema implements JsonSerializable
{
    /** @param array<int,ConfigField> $fields */
    public function __construct(public array $fields = [])
    {
    }

    public function jsonSerialize(): array
    {
        return [
            'fields' => array_map(
                static fn(ConfigField $f) => $f->jsonSerialize(),
                $this->fields
            ),
        ];
    }
}