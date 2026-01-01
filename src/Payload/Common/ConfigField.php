<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

use JsonSerializable;

final readonly class ConfigField implements JsonSerializable
{
    /**
     * @param array<int,string> $rules
     * @param array<int,ConfigFieldOption> $options
     */
    public function __construct(
        public string  $name,
        public string  $label,
        public string  $type = 'text',
        public bool    $required = false,
        public array   $rules = [],
        public mixed   $default = null,
        public ?string $helpText = null,
        public array   $options = [],
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'label' => $this->label,
            'type' => $this->type,
            'required' => $this->required,
            'rules' => $this->rules,
            'default' => $this->default,
            'helpText' => $this->helpText,
            'options' => array_map(
                static fn (ConfigFieldOption $o) => $o->jsonSerialize(),
                $this->options
            ),
        ];
    }
}