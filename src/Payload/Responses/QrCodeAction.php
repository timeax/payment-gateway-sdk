<?php declare(strict_types=1);

namespace PayKit\Payload\Responses;

final readonly class QrCodeAction implements NextAction
{
    public function __construct(
        public string  $payload,
        public ?string $format = null, // e.g. "text"|"url"|"emvco"
    )
    {
    }

    public function type(): string
    {
        return 'qrcode';
    }

    public function jsonSerialize(): array
    {
        return [
            'type' => $this->type(),
            'payload' => $this->payload,
            'format' => $this->format,
        ];
    }
}