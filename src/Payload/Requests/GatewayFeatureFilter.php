<?php declare(strict_types=1);

namespace PayKit\Payload\Requests;

use JsonSerializable;
use PayKit\Payload\Common\GatewayFeatureSet;

/**
 * Tri-state feature filter:
 * - null  => ignore
 * - true  => must support
 * - false => must NOT support (optional use-case; keep for completeness)
 */
final readonly class GatewayFeatureFilter implements JsonSerializable
{
    public function __construct(
        public ?bool $payments = null,
        public ?bool $verification = null,
        public ?bool $scripts = null,

        public ?bool $ui = null,
        public ?bool $frontendConfig = null,

        public ?bool $refunds = null,
        public ?bool $disputes = null,

        public ?bool $savedMethods = null,
        public ?bool $cardTokenization = null,

        public ?bool $virtualAccounts = null,
        public ?bool $virtualAccountLedger = null,
        public ?bool $virtualAccountWithdrawals = null,
        public ?bool $virtualAccountPollingWatcher = null,

        public ?bool $payouts = null,
        public ?bool $beneficiaries = null,
        public ?bool $virtualCards = null,

        public ?bool $reconcile = null,
        public ?bool $diagnostics = null,
    )
    {
    }

    public function matches(GatewayFeatureSet $set): bool
    {
        foreach (get_object_vars($this) as $k => $want) {
            if ($want === null) {
                continue;
            }

            // if filter says true/false, enforce exact match
            if (!property_exists($set, $k)) {
                continue;
            }

            /** @var mixed $actual */
            $actual = $set->{$k};

            if ((bool)$actual !== (bool)$want) {
                return false;
            }
        }

        return true;
    }

    public function jsonSerialize(): array
    {
        return [
            'payments' => $this->payments,
            'verification' => $this->verification,
            'scripts' => $this->scripts,
            'ui' => $this->ui,
            'frontendConfig' => $this->frontendConfig,
            'refunds' => $this->refunds,
            'disputes' => $this->disputes,
            'savedMethods' => $this->savedMethods,
            'cardTokenization' => $this->cardTokenization,
            'virtualAccounts' => $this->virtualAccounts,
            'virtualAccountLedger' => $this->virtualAccountLedger,
            'virtualAccountWithdrawals' => $this->virtualAccountWithdrawals,
            'virtualAccountPollingWatcher' => $this->virtualAccountPollingWatcher,
            'payouts' => $this->payouts,
            'beneficiaries' => $this->beneficiaries,
            'virtualCards' => $this->virtualCards,
            'reconcile' => $this->reconcile,
            'diagnostics' => $this->diagnostics,
        ];
    }

    public function isEmpty(): bool
    {
        foreach ($this->jsonSerialize() as $v) {
            if ($v !== null) {
                return false;
            }
        }
        return true;
    }
}

