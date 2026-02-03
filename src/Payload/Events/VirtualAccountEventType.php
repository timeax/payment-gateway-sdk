<?php declare(strict_types=1);

namespace PayKit\Payload\Events;

/**
 * Normalized virtual account event type.
 *
 * Notes:
 * - Keep these stable and provider-agnostic.
 * - Provider-specific nuance belongs in meta/rawProviderPayload.
 */
enum VirtualAccountEventType: string
{
    // --- lifecycle ---
    case created = 'created';
    case assigned = 'assigned';
    case updated = 'updated';
    case deactivated = 'deactivated';

    // --- ledger movements ---
    case credited = 'credited'; // funds in (deposit/credit)
    case debited = 'debited';   // funds out (debit)

    // --- withdrawals (if supported) ---
    case withdrawal_initiated = 'withdrawal_initiated';
    case withdrawal_updated = 'withdrawal_updated';
    case withdrawal_succeeded = 'withdrawal_succeeded';
    case withdrawal_failed = 'withdrawal_failed';
    case withdrawal_cancelled = 'withdrawal_cancelled';

    // --- backfill / reconciliation ---
    case backfilled = 'backfilled';

    // --- fallback ---
    case other = 'other';
}