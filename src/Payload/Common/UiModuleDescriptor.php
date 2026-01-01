<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

use JsonSerializable;

final readonly class UiModuleDescriptor implements JsonSerializable
{
    /** @param array<string,mixed> $meta */
    public function __construct(
        public string  $key,
        public string  $label,
        public ?string $version = null,
        public array   $meta = [],
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'key' => $this->key,
            'label' => $this->label,
            'version' => $this->version,
            'meta' => $this->meta,
        ];
    }
}