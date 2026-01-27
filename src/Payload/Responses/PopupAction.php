<?php declare(strict_types=1);

namespace PayKit\Payload\Responses;

final readonly class PopupAction implements NextAction
{
    /** @param array<string,mixed> $uiProps */
    public function __construct(
        public string $uiEntry,            // e.g. "stripe.pay.popup"
        public array $uiProps = [],
        public array $clientConfig = [],
        public ?PopupSpec $popup = null,
    ) {}

    public function type(): string
    {
        return 'popup';
    }

    public function jsonSerialize(): array
    {
        return [
            'type' => $this->type(),
            'uiEntry' => $this->uiEntry,
            'uiProps' => $this->uiProps,
            'clientConfig' => $this->clientConfig,
            'popup' => $this->popup?->jsonSerialize(),
        ];
    }
}

