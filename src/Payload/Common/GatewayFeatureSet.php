<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

use JsonSerializable;

final readonly class GatewayFeatureSet implements JsonSerializable
{
    public function __construct(
        public bool $payments = true,

        public bool $verification = false,
        public bool $webhooks = false,

        public bool $scripts = false,
        public bool $ui = false,
        public bool $frontendConfig = false,

        public bool $refunds = false,
        public bool $disputes = false,

        public bool $savedMethods = false,
        public bool $cardTokenization = false,

        public bool $virtualAccounts = false,
        public bool $virtualAccountLedger = false,
        public bool $virtualAccountWithdrawals = false,
        public bool $virtualAccountWebhookWatcher = false,
        public bool $virtualAccountPollingWatcher = false,
        public bool $virtualAccountReconcile = false,

        public bool $payouts = false,
        public bool $beneficiaries = false,

        public bool $virtualCards = false,

        public bool $reconcile = false,
        public bool $diagnostics = false,
    ) {}

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}

