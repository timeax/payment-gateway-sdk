<?php declare(strict_types=1);

namespace PayKit\Payload\Events;

use JsonSerializable;
use PayKit\Payload\Responses\WebhookVerifyResult;

final readonly class WebhookHandleResult implements JsonSerializable
{
    public function __construct(
        public WebhookVerifyResult $verified,
        public ?WebhookEvent       $event = null,
    )
    {
    }

    public static function rejected(WebhookVerifyResult $verified): self
    {
        return new self($verified, null);
    }

    public static function accepted(WebhookVerifyResult $verified, WebhookEvent $event): self
    {
        return new self($verified, $event);
    }

    public function jsonSerialize(): array
    {
        return [
            'verified' => $this->verified->jsonSerialize(),
            'event' => $this->event?->jsonSerialize(),
        ];
    }
}

