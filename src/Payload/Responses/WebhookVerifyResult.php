<?php declare(strict_types=1);

namespace PayKit\Payload\Responses;

use JsonSerializable;

final readonly class WebhookVerifyResult implements JsonSerializable
{
    /**
     * @param array<string,mixed> $errors
     * @param array<string,mixed> $meta safe verification metadata (event_id, signature headers, timestamp, etc.)
     * @param array<string,mixed>|string|null $rawProviderPayload decoded/normalized raw payload (optional)
     */
    public function __construct(
        public bool              $ok,
        public ?string           $reason = null,
        public array             $errors = [],
        public array|string|null $rawProviderPayload = null,
        public array             $meta = [],
    )
    {
    }

    /** @param array<string,mixed>|string|null $rawProviderPayload */
    public static function ok(array|string|null $rawProviderPayload = null, array $meta = []): self
    {
        return new self(true, null, [], $rawProviderPayload, $meta);
    }

    /** @param array<string,mixed> $errors @param array<string,mixed>|string|null $rawProviderPayload */
    public static function fail(string $reason, array $errors = [], array|string|null $rawProviderPayload = null, array $meta = []): self
    {
        return new self(false, $reason, $errors, $rawProviderPayload, $meta);
    }

    public function jsonSerialize(): array
    {
        return [
            'ok' => $this->ok,
            'reason' => $this->reason,
            'errors' => (object)$this->errors,
            'rawProviderPayload' => $this->rawProviderPayload,
            'meta' => (object)$this->meta,
        ];
    }
}

