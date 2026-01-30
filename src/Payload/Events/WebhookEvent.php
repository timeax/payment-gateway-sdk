<?php declare(strict_types=1);

namespace PayKit\Payload\Events;

use JsonSerializable;
use PayKit\Payload\Common\CanonicalPaymentStatus;
use PayKit\Payload\Common\CanonicalPayoutStatus;
use PayKit\Payload\Common\CanonicalRefundStatus;
use PayKit\Payload\Common\Metadata;
use PayKit\Payload\Common\ProviderRef;
use PayKit\Payload\Common\Reference;

final readonly class WebhookEvent implements JsonSerializable
{
    public Metadata $meta;

    /**
     * Generic normalized webhook event.
     *
     * Examples:
     * - subject: payment, type: updated, paymentStatus: succeeded
     * - subject: refund,  type: updated, refundStatus: processing
     * - subject: payout,  type: updated, payoutStatus: failed
     *
     * @param array<string,mixed>|string|null $rawProviderPayload
     */
    public function __construct(
        public WebhookEventSubject     $subject,
        public WebhookEventType        $type,

        public ?Reference              $reference = null,
        public ?ProviderRef            $providerRef = null,

        public ?CanonicalPaymentStatus $paymentStatus = null,
        public ?CanonicalRefundStatus  $refundStatus = null,
        public ?CanonicalPayoutStatus  $payoutStatus = null,

        ?Metadata                      $meta = null,
        public array|string|null       $rawProviderPayload = null,
    )
    {
        $this->meta = $meta ?? new Metadata([]);
    }

    public function jsonSerialize(): array
    {
        return [
            'subject' => $this->subject->value,
            'type' => $this->type->value,
            'reference' => $this->reference?->toString(),
            'providerRef' => $this->providerRef?->toString(),
            'paymentStatus' => $this->paymentStatus?->value,
            'refundStatus' => $this->refundStatus?->value,
            'payoutStatus' => $this->payoutStatus?->value,
            'meta' => $this->meta->toArray(),
            'rawProviderPayload' => $this->rawProviderPayload,
        ];
    }
}

