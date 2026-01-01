<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

use JsonSerializable;

final readonly class UiEntryDescriptor implements JsonSerializable
{
    /**
     * Example:
     * - key: "stripe.settings"
     * - module: "stripe"
     * - target: "settings"|"checkout"|"diagnostics"
     *
     * @param array<string,mixed>|null $props
     * @param array<string,mixed>|null $schema
     * @param array<int,string>|null $permissions
     */
    public function __construct(
        public string  $key,
        public string  $module,
        public string  $target,
        public ?string $label = null,
        public ?array  $props = null,
        public ?array  $schema = null,
        public ?array  $permissions = null,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'key' => $this->key,
            'module' => $this->module,
            'target' => $this->target,
            'label' => $this->label,
            'props' => $this->props,
            'schema' => $this->schema,
            'permissions' => $this->permissions,
        ];
    }
}