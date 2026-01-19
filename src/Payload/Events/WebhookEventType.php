<?php declare(strict_types=1);

namespace PayKit\Payload\Events;

/**
 * Normalized webhook "type" (what happened to the subject).
 *
 * Keep this stable and semantic. Provider-specific event names belong in
 * rawProviderPayload/meta (e.g. meta['providerEvent'] = 'payment_intent.succeeded').
 */
enum WebhookEventType: string
{
    // generic lifecycle
    case created = 'created';
    case updated = 'updated';
    case deleted = 'deleted';

    // success/failure outcomes
    case succeeded = 'succeeded';
    case failed = 'failed';
    case cancelled = 'cancelled';
    case expired = 'expired';

    // state transitions that often matter
    case processing = 'processing';
    case pending = 'pending';

    // “needs host/user action” (3DS, extra steps, etc.)
    case requires_action = 'requires_action';

    // verification/validation events (bank account verified, destination verified, etc.)
    case verified = 'verified';
    case rejected = 'rejected';

    // reversals / chargebacks / disputes flow
    case reversed = 'reversed';        // payout reversed / refund reversed
    case disputed = 'disputed';        // dispute opened/raised
    case dispute_won = 'dispute_won';
    case dispute_lost = 'dispute_lost';

    // funding / balance-like (virtual account deposits, ledger credit/debit)
    case credited = 'credited';
    case debited = 'debited';

    // fallback
    case other = 'other';
}