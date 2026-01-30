<?php declare(strict_types=1);

namespace PayKit\Payload\Events;

/**
 * Normalized webhook "subject" (what entity the event is ABOUT).
 *
 * Keep these fairly coarse and stable:
 * - subjects map to SDK domains/resources (payments, refunds, payouts, etc.)
 * - provider-specific object types go into rawProviderPayload/meta
 * - unknown/new provider types => `other`
 */
enum WebhookEventSubject: string
{
    // --- payments ---
    case payment = 'payment';                 // charge / intent / invoice payment / authorization
    case payment_method = 'payment_method';   // saved method attached/updated/detached (provider "payment method" objects)
    case card_token = 'card_token';           // tokenization result / setup token / card token

    // --- refunds / disputes ---
    case refund = 'refund';
    case dispute = 'dispute';

    // --- payouts / beneficiaries ---
    case payout = 'payout';                   // outbound transfer / payout
    case beneficiary = 'beneficiary';         // recipient / beneficiary changes
    case payout_destination = 'payout_destination'; // bank/wallet destination created/updated/verified (optional but useful)

    // --- virtual accounts ---
    case virtual_account = 'virtual_account'; // VA created/assigned/disabled
    case virtual_account_event = 'virtual_account_event'; // deposit/transfer notifications (watchers)
    case virtual_account_ledger = 'virtual_account_ledger'; // ledger entry created/updated (if provider pushes)
    case virtual_account_withdrawal = 'virtual_account_withdrawal';

    // --- virtual cards ---
    case virtual_card = 'virtual_card';       // card created/frozen/charged/etc. (if provider supports)

    // --- reconciliation / diagnostics (rare, but keeps model complete) ---
    case reconcile = 'reconcile';             // backfill/reconcile job notifications (if any)
    case diagnostics = 'diagnostics';         // provider health/diagnostic callbacks (rare)

    // --- fallback / extensions ---
    case other = 'other';
}

