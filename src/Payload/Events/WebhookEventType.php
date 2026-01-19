<?php declare(strict_types=1);

namespace PayKit\Payload\Events;

/**
 * Keep this about the *event action*, not the status.
 * Status should live in paymentStatus/refundStatus/payoutStatus.
 */
enum WebhookEventType: string
{
    case created = 'created';
    case updated = 'updated';
    case deleted = 'deleted';

    // For “we received something but it doesn't map cleanly”
    case other = 'other';
}