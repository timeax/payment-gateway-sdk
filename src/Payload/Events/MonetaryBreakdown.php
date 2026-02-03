<?php declare(strict_types=1);

namespace PayKit\Payload\Events;

use JsonSerializable;
use PayKit\Payload\Common\Money;

final readonly class MonetaryBreakdown implements JsonSerializable
{
    /**
     * Monetary snapshot for a webhook event.
     *
     * These are intentionally generic because the SDK normalizes events
     * across pay-ins, refunds, payouts, virtual account movements, etc.
     *
     * Suggested interpretation:
     * - amount:         the primary amount the event is about (requested/initiated/gross)
     * - fee:            provider fee(s) for this event (if known)
     * - amountPaid:     amount that was actually paid by the payer / charged (if known)
     * - amountAccepted: amount the provider accepted for processing (risk/validation/etc.)
     * - amountSettled:  amount that became confirmed/settled/available (if known)
     * - amountNet:      net amount (typically settled - fee) if provider supplies it
     *
     * NOTE:
     * - Not all providers supply all fields.
     * - For refunds/payouts, “paid” can be interpreted as “moved” (funds sent/charged).
     */
    public function __construct(
        public ?Money $amount = null,
        public ?Money $fee = null,

        public ?Money $amountPaid = null,
        public ?Money $amountAccepted = null,
        public ?Money $amountSettled = null,
        public ?Money $amountNet = null,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'amount' => $this->amount?->jsonSerialize(),
            'fee' => $this->fee?->jsonSerialize(),
            'amountPaid' => $this->amountPaid?->jsonSerialize(),
            'amountAccepted' => $this->amountAccepted?->jsonSerialize(),
            'amountSettled' => $this->amountSettled?->jsonSerialize(),
            'amountNet' => $this->amountNet?->jsonSerialize(),
        ];
    }

    public function isEmpty(): bool
    {
        return $this->amount === null
            && $this->fee === null
            && $this->amountPaid === null
            && $this->amountAccepted === null
            && $this->amountSettled === null
            && $this->amountNet === null;
    }
}