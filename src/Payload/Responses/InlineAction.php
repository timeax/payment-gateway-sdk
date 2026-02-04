<?php declare(strict_types=1);

namespace PayKit\Payload\Responses;

use PayKit\Payload\Common\GatewayScript;

final readonly class InlineAction implements NextAction
{
    /**
     * @var array<GatewayScript>
     */
    protected ?array $scripts;

    /**
     * @param array<int,string> $scriptKeys Keys that must be present/loaded before rendering inline flow.
     * @param array<string,mixed> $clientConfig Provider-specific config (opaque to SDK).
     */
    public function __construct(
        public array            $scriptKeys = [],     // e.g. ["stripe-js"]
        public array            $clientConfig = [],
        public ?InlineMountSpec $mount = null,
    )
    {
    }

    public function setScripts(array $scripts): void
    {
        $this->scripts = $scripts;
    }

    public function type(): string
    {
        return 'inline';
    }


    public function jsonSerialize(): array
    {
        return [
            'type' => $this->type(),
            'scriptKeys' => $this->scriptKeys,
            'scripts' => $this->scripts,
            'clientConfig' => $this->clientConfig,
            'mount' => $this->mount?->jsonSerialize(),
        ];
    }
}

