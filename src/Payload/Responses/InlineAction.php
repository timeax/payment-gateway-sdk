<?php declare(strict_types=1);

namespace PayKit\Payload\Responses;

final readonly class InlineAction implements NextAction
{
    /** @param array<string,mixed> $clientConfig */
    public function __construct(public array $clientConfig = [])
    {
    }

    public function type(): string
    {
        return 'inline';
    }

    public function jsonSerialize(): array
    {
        return [
            'type' => $this->type(),
            'clientConfig' => $this->clientConfig,
        ];
    }
}