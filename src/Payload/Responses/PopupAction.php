<?php declare(strict_types=1);

namespace PayKit\Payload\Responses;

final readonly class PopupAction implements NextAction
{
    /** @param array<string,mixed> $clientConfig */
    public function __construct(public array $clientConfig = [])
    {
    }

    public function type(): string
    {
        return 'popup';
    }

    public function jsonSerialize(): array
    {
        return [
            'type' => $this->type(),
            'clientConfig' => $this->clientConfig,
        ];
    }
}