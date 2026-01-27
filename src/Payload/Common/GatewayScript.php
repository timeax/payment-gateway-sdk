<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

use JsonSerializable;

final readonly class GatewayScript implements JsonSerializable
{
    /**
     * @param array<string,string> $attributes
     */
    public function __construct(
        public ScriptLocation $location,
        public string         $key,
        public ?string        $src = null,
        public ?string        $inline = null,
        public bool           $async = false,
        public bool           $defer = true,
        public ?string        $integrity = null,
        public ?string        $crossorigin = null,
        public ?string        $referrerPolicy = null,
        public array          $attributes = [],
    )
    {
    }

    public function jsonSerialize(): array
    {
        return [
            'key' => $this->key,
            'location' => $this->location->value,
            'src' => $this->src,
            'inline' => $this->inline,
            'async' => $this->async,
            'defer' => $this->defer,
            'integrity' => $this->integrity,
            'crossorigin' => $this->crossorigin,
            'referrerPolicy' => $this->referrerPolicy,
            'attributes' => $this->attributes,
        ];
    }
}

