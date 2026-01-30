<?php declare(strict_types=1);

namespace PayKit\Payload\Responses;

final readonly class InlineAction implements NextAction
{
    /**
     * @param array<int,string> $scriptKeys Keys that must be present/loaded before rendering inline flow.
     * @param array<string,mixed> $clientConfig Provider-specific config (opaque to SDK).
     */
    public function __construct(
        public array $scriptKeys = [],     // e.g. ["stripe-js"]
        public array $clientConfig = [],
        public ?InlineMountSpec $mount = null,
    ) {}

    public function type(): string
    {
        return 'inline';
    }

    public function jsonSerialize(): array
    {
        return [
            'type' => $this->type(),
            'scriptKeys' => $this->scriptKeys,
            'clientConfig' => $this->clientConfig,
            'mount' => $this->mount?->jsonSerialize(),
        ];
    }
}

