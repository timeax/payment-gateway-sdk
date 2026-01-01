<?php declare(strict_types=1);

namespace PayKit\Payload\Responses;

use JsonSerializable;

final readonly class WebhookVerifyResult implements JsonSerializable
{
    public function __construct(
        public bool    $ok,
        public ?string $message = null,
    ) {}

    public static function ok(): self
    {
        return new self(true, null);
    }

    public static function fail(string $message): self
    {
        return new self(false, $message);
    }

    public function jsonSerialize(): array
    {
        return [
            'ok' => $this->ok,
            'message' => $this->message,
        ];
    }
}