# 📦 PayKit Gateway SDK (Contracts + Payloads)

A **business‑agnostic**, **contract‑driven** payment gateway SDK for any host application.

This library defines:

* **Contracts (interfaces)**: the rules a gateway driver must implement.
* **Payloads**: strict request/response/event shapes (no magic arrays).
* **Abstract driver base classes**: helpers and safe defaults.
* **A Manager/Registry**: resolves the correct driver for a gateway record.
* **Frontend integration primitives**: script injection + UI module manifests.

> ✅ **Not included:** checkout/cart/invoice semantics. The SDK never knows “what payment is for”.

---

## 1) Core philosophy

### 1. Model‑First (Host DB is Source of Truth)

* Supported currencies/countries/features are persisted by the host into its **PaymentGateway model** (or equivalent).
* Runtime filtering happens from DB/model — the SDK does **not** call provider APIs at runtime to discover currencies.

### 2. Host‑Controlled Business Effects

* Drivers only **initiate**, **verify**, **parse events**, and **normalize statuses**.
* The host decides what “success” means (credit wallet, fulfill order, mark invoice paid, etc.).

### 3. Strict Contracts + Payloads

* Every driver method uses typed request/response payloads.
* No untyped arrays for core operations.

### 4. Capability‑by‑Contract

* Optional features are represented by optional interfaces.
* A driver “supports refunds” because it implements `PaymentGatewayRefundsContract`.

---

## 2) What this SDK solves

* Integrate Stripe/PayPal/Flutterwave/Paystack/Crypto/etc. as drivers.

* The host can list gateways and filter by:

    * currency support
    * country support
    * enabled/disabled
    * feature support (webhooks, saved methods, virtual accounts, payouts)

* The host can render provider UI needs:

    * scripts (Stripe.js)
    * UI modules (Pay widget, Settings screen, Diagnostics)

---

## 3) Glossary

* **Host**: the application consuming this SDK.
* **Gateway**: a provider integration (Stripe, PayPal…).
* **Driver**: a class implementing contracts for a specific gateway.
* **reference**: host‑provided identifier (string). The SDK does not care what it represents.
* **providerRef**: the provider’s transaction/intent identifier.
* **Manifest**: driver‑declared support matrix (currencies, features, UI modules) persisted by the host.

---

## 4) Config injection (Hybrid)

PayKit uses a **hybrid config injection** pattern:

* Drivers may receive a **default config** in the constructor (standard “manager” flow).
* Contract methods accept an **optional override config**: `?GatewayConfig $config = null`.
* Resolution rule: **method override > constructor default > error**.

This keeps host code clean in normal flows, while allowing stateless/batch processing when needed (e.g., iterating through multiple merchant accounts).

**Rule:** Wherever a method takes both a config and other parameters, the config **must be the last parameter**.

---

## 5) `src/` folder structure (reference)

> This is the canonical `src/` layout.

```text
src/
  index.php
  Pay.php

  Contracts/
    # --- base / discovery ---
    PaymentGatewayDriverContract.php
    PaymentGatewayManifestProviderContract.php
    PaymentGatewayAvailabilityContract.php
    PaymentGatewayRequirementsContract.php
    ProvidesGatewayConfigContract.php

    # --- core payments ---
    PaymentGatewayPaymentsContract.php
    PaymentGatewayPaymentStatusMapperContract.php
    PaymentGatewayVerificationContract.php

    # --- webhooks / events ---
    PaymentGatewayWebhooksContract.php

    # --- frontend integration ---
    PaymentGatewayScriptsContract.php
    PaymentGatewayUiContract.php
    PaymentGatewayFrontendConfigContract.php

    # --- saved cards / methods ---
    PaymentGatewaySavedMethodsContract.php
    PaymentGatewayCardTokenizationContract.php

    # --- refunds / disputes ---
    PaymentGatewayRefundsContract.php
    PaymentGatewayDisputesContract.php

    # --- virtual accounts (deep) ---
    PaymentGatewayVirtualAccountsContract.php
    PaymentGatewayVirtualAccountLedgerContract.php
    PaymentGatewayVirtualAccountWithdrawalsContract.php
    PaymentGatewayVirtualAccountWebhookWatcherContract.php
    PaymentGatewayVirtualAccountPollingWatcherContract.php
    PaymentGatewayVirtualAccountReconcileContract.php

    # --- payouts (general) ---
    PaymentGatewayPayoutsContract.php
    PaymentGatewayBeneficiariesContract.php

    # --- virtual cards (optional) ---
    PaymentGatewayVirtualCardsContract.php

    # --- reconciliation / diagnostics ---
    PaymentGatewayReconcileContract.php
    PaymentGatewayDiagnosticsContract.php

  Payload/
    Common/
      # primitives
      Money.php
      Amount.php
      Currency.php
      Country.php
      Reference.php
      ProviderRef.php
      Metadata.php
      CanonicalPaymentStatus.php
      CanonicalPayoutStatus.php
      CanonicalRefundStatus.php

      # config + schema
      GatewayConfig.php
      GatewayConfigSchema.php
      ConfigField.php
      ConfigFieldOption.php
      ConfigValidationError.php
      ValidationResult.php
      HealthCheckResult.php

      # manifest + capabilities
      GatewayManifest.php
      GatewayFeatureSet.php
      GatewaySupportMatrix.php
      SupportedCurrency.php
      SupportedCountry.php
      GatewayRequirements.php
      UiManifest.php
      UiModuleDescriptor.php
      UiEntryDescriptor.php

      # scripts
      GatewayScript.php
      ScriptLocation.php

      # saved methods/cards
      SavedMethod.php
      CardSummary.php
      CardBrand.php
      CardFingerprint.php

      # virtual accounts/cards
      VirtualAccount.php
      VirtualAccountBank.php
      VirtualCardRecord.php

      # payouts
      PayoutDestination.php
      PayoutMethod.php
      Beneficiary.php

    Requests/
      # payments
      PaymentInitiateRequest.php
      PaymentVerifyRequest.php

      # webhooks
      WebhookRequest.php

      # refunds/disputes
      RefundRequest.php
      RefundVerifyRequest.php
      DisputeQuery.php

      # saved methods/cards
      ListMethodsRequest.php
      GetMethodRequest.php
      AttachMethodRequest.php
      DetachMethodRequest.php
      SetDefaultMethodRequest.php
      CardTokenizeRequest.php

      # virtual accounts
      VirtualAccountCreateRequest.php
      VirtualAccountAssignRequest.php
      VirtualAccountGetRequest.php
      ListVirtualAccountsRequest.php
      DeactivateVirtualAccountRequest.php
      VirtualAccountLedgerQuery.php
      VirtualAccountLedgerEntryQuery.php
      VirtualAccountWithdrawalRequest.php
      VirtualAccountWithdrawalVerifyRequest.php

      # watchers / polling
      PollVirtualAccountEventsQuery.php
      ReconcileQuery.php

      # payouts
      PayoutRequest.php
      PayoutVerifyRequest.php
      BeneficiaryCreateRequest.php
      BeneficiaryUpdateRequest.php

      # frontend config
      FrontendConfigRequest.php

    Responses/
      # payments + next action
      PaymentInitiateResult.php
      NextAction.php
      RedirectAction.php
      InlineAction.php
      PopupAction.php
      QrCodeAction.php
      InstructionsAction.php

      PaymentVerifyResult.php

      # webhooks
      WebhookVerifyResult.php

      # refunds/disputes
      RefundResult.php
      RefundStatusResult.php
      DisputeSnapshot.php

      # saved methods/cards
      SavedMethodList.php
      CardTokenizeResult.php

      # virtual accounts
      VirtualAccountRecord.php
      VirtualAccountList.php
      VirtualAccountLedgerEntry.php
      VirtualAccountLedgerPage.php
      VirtualAccountWithdrawalResult.php
      VirtualAccountWithdrawalStatusResult.php

      # watchers / reconcile
      PollSpec.php
      ReconcileResult.php

      # payouts
      PayoutResult.php
      PayoutStatusResult.php
      BeneficiaryList.php

      # frontend config
      FrontendConfigResult.php

    Events/
      # generic webhook normalization
      WebhookEvent.php
      WebhookHandleResult.php

      # virtual account events (deposits/withdrawals/transfers)
      VirtualAccountEvent.php
      VirtualAccountEventBatch.php

      # optional payout/refund normalization
      PayoutEvent.php
      RefundEvent.php

  Drivers/
    AbstractPaymentGatewayDriver.php
    Concerns/
      ResolvesConfig.php
      HasConfigSchema.php
      MapsStatuses.php
      RedactsSecrets.php
      BuildsManifest.php

  Manager/
    GatewayRegistry.php
    GatewayManager.php
    DriverResolver.php

  Support/
    Assert.php
    Clock.php
    Redactor.php
    Signature.php
    Idempotency.php
    Pagination.php
    DedupeScripts.php

  Exceptions/
    GatewayDriverNotFoundException.php
    GatewayConfigException.php
    GatewayCapabilityException.php
    GatewayRuntimeException.php

  Types/
    Dict.php
```

---

## 6) Canonical statuses and normalization

### CanonicalPaymentStatus

A closed set to keep the host consistent:

* `pending`
* `processing`
* `succeeded`
* `failed`
* `cancelled`
* `refunded`

Drivers must normalize provider-specific states into this set.

---

## 7) Contracts (Interfaces)

All interfaces live under `Contracts/`.

### 7.1 Base driver contract (Required)

**Why:** The host must be able to:

* resolve the correct driver
* render an admin settings form
* validate settings before enabling
* expose safe public config for UI
* run a driver‑implemented health check

```php
namespace PayKit\Contracts;

use PayKit\Payload\Common\GatewayConfig;
use PayKit\Payload\Common\GatewayConfigSchema;
use PayKit\Payload\Common\ValidationResult;
use PayKit\Payload\Common\HealthCheckResult;

interface PaymentGatewayDriverContract
{
    public function driverKey(): string;

    public function configSchema(): GatewayConfigSchema;

    public function validateConfig(?GatewayConfig $config = null): ValidationResult;

    public function publicConfig(?GatewayConfig $config = null): array; // safe keys only

    public function healthCheck(?GatewayConfig $config = null): HealthCheckResult;

    public function redactForLogs(mixed $payload): mixed;
}
```

**Host usage (typical):**

* Build admin UI from `configSchema()`.
* Validate input with `validateConfig()` before enabling.
* Provide frontend-only keys via `publicConfig()`.
* Show admin diagnostics via `healthCheck()`.

---

### 7.2 Manifest provider contract (Strongly recommended)

**Why:** The driver “teaches” the host DB what it supports:

* currencies
* countries
* features
* UI modules
* required fields for initiation

The host persists this into its PaymentGateway model + related storage.

```php
namespace PayKit\Contracts;

use PayKit\Payload\Common\GatewayConfig;
use PayKit\Payload\Common\GatewayManifest;

interface PaymentGatewayManifestProviderContract
{
    public function getManifest(?GatewayConfig $config = null): GatewayManifest;
}
```

**Host usage:** call during install/enable/config change/admin refresh to persist the snapshot. Runtime checkout filtering stays DB-only.

---

### 7.3 Availability contract (Optional)

**Why:** Some gateways should be hidden/disabled dynamically (maintenance, rate-limits, geographic blocks beyond the DB snapshot, etc.).

```php
namespace PayKit\Contracts;

use PayKit\Payload\Common\GatewayConfig;

interface PaymentGatewayAvailabilityContract
{
    public function isAvailable(array $context = [], ?GatewayConfig $config = null): bool;
}
```

**Host usage:** call right before presenting/using a gateway if you need dynamic hiding.

---

### 7.4 Requirements contract (Optional)

**Why:** Some gateways require extra data collection (billing address, phone, KYC id). This contract lets the driver declare it.

```php
namespace PayKit\Contracts;

use PayKit\Payload\Common\GatewayConfig;
use PayKit\Payload\Common\GatewayRequirements;

interface PaymentGatewayRequirementsContract
{
    public function requirements(array $context = [], ?GatewayConfig $config = null): GatewayRequirements;
}
```

**Host usage:** build UI/validation based on returned requirements (often merged with manifest requirements).

---

### 7.5 Payments contract (Core)

**Why:** Start a payment and return a **NextAction** (redirect/inline/instructions).

```php
namespace PayKit\Contracts;

use PayKit\Payload\Common\GatewayConfig;
use PayKit\Payload\Requests\PaymentInitiateRequest;
use PayKit\Payload\Responses\PaymentInitiateResult;
use PayKit\Payload\Common\CanonicalPaymentStatus;

interface PaymentGatewayPaymentsContract
{
    public function initiatePayment(PaymentInitiateRequest $request, ?GatewayConfig $config = null): PaymentInitiateResult;

    /** Normalize provider payload to canonical status. */
    public function mapStatus(mixed $rawPayload): CanonicalPaymentStatus;
}
```

**Host usage:** always treat initiation as “what should the user do next?” via `NextAction`.

---

### 7.6 Payment status mapper (Core)

**Why:** Some hosts want status mapping separated from initiation logic.

```php
namespace PayKit\Contracts;

use PayKit\Payload\Common\CanonicalPaymentStatus;

interface PaymentGatewayPaymentStatusMapperContract
{
    public function mapStatus(mixed $rawPayload): CanonicalPaymentStatus;
}
```

---

### 7.7 Verification contract (Optional)

**Why:** Some hosts prefer server-side polling to confirm success.

```php
namespace PayKit\Contracts;

use PayKit\Payload\Common\GatewayConfig;
use PayKit\Payload\Requests\PaymentVerifyRequest;
use PayKit\Payload\Responses\PaymentVerifyResult;

interface PaymentGatewayVerificationContract
{
    public function verifyPayment(PaymentVerifyRequest $request, ?GatewayConfig $config = null): PaymentVerifyResult;
}
```

---

### 7.8 Webhooks contract (Optional)

**Why:** Push-based updates. Must verify signatures and parse events.

```php
namespace PayKit\Contracts;

use PayKit\Payload\Common\GatewayConfig;
use PayKit\Payload\Requests\WebhookRequest;
use PayKit\Payload\Responses\WebhookVerifyResult;
use PayKit\Payload\Events\WebhookEvent;

interface PaymentGatewayWebhooksContract
{
    public function verifyWebhook(WebhookRequest $request, ?GatewayConfig $config = null): WebhookVerifyResult;

    public function parseWebhook(WebhookRequest $request, ?GatewayConfig $config = null): WebhookEvent;
}
```

---

### 7.9 Scripts contract (Optional)

**Why:** Script injection for provider SDKs (Stripe.js etc.).

```php
namespace PayKit\Contracts;

use PayKit\Payload\Common\GatewayConfig;
use PayKit\Payload\Common\GatewayScript;

interface PaymentGatewayScriptsContract
{
    /** @return array<int, GatewayScript> */
    public function getScripts(?GatewayConfig $config = null): array;
}
```

---

### 7.10 UI manifest contract (Optional)

**Why:** Drivers may expose UI modules beyond scripts (pay widget, settings UI, diagnostics UI).

```php
namespace PayKit\Contracts;

use PayKit\Payload\Common\UiManifest;

interface PaymentGatewayUiContract
{
    public function uiManifest(): UiManifest;
}
```

> If you want framework-specific UI, publish a separate package (e.g. `paykit-ui-react`).

---

### 7.11 Frontend config contract (Optional)

**Why:** Some gateways require per-session/per-payment computed config (client secrets, ephemeral keys).

```php
namespace PayKit\Contracts;

use PayKit\Payload\Common\GatewayConfig;
use PayKit\Payload\Requests\FrontendConfigRequest;
use PayKit\Payload\Responses\FrontendConfigResult;

interface PaymentGatewayFrontendConfigContract
{
    public function getFrontendConfig(FrontendConfigRequest $request, ?GatewayConfig $config = null): FrontendConfigResult;
}
```

---

### 7.12 Refunds contract (Optional)

```php
namespace PayKit\Contracts;

use PayKit\Payload\Common\GatewayConfig;
use PayKit\Payload\Requests\RefundRequest;
use PayKit\Payload\Responses\RefundResult;
use PayKit\Payload\Requests\RefundVerifyRequest;
use PayKit\Payload\Responses\RefundStatusResult;

interface PaymentGatewayRefundsContract
{
    public function refund(RefundRequest $request, ?GatewayConfig $config = null): RefundResult;

    public function verifyRefund(RefundVerifyRequest $request, ?GatewayConfig $config = null): ?RefundStatusResult;
}
```

---

### 7.13 Disputes contract (Optional)

```php
namespace PayKit\Contracts;

use PayKit\Payload\Common\GatewayConfig;
use PayKit\Payload\Requests\DisputeQuery;
use PayKit\Payload\Responses\DisputeSnapshot;

interface PaymentGatewayDisputesContract
{
    public function getDispute(DisputeQuery $query, ?GatewayConfig $config = null): ?DisputeSnapshot;
}
```

---

## 8) Saved methods / cards

### 8.1 Card tokenization contract (Optional)

**Why:** A gateway may support card capture/tokenization.

```php
namespace PayKit\Contracts;

use PayKit\Payload\Common\GatewayConfig;
use PayKit\Payload\Requests\CardTokenizeRequest;
use PayKit\Payload\Responses\CardTokenizeResult;

interface PaymentGatewayCardTokenizationContract
{
    public function tokenizeCard(CardTokenizeRequest $request, ?GatewayConfig $config = null): CardTokenizeResult;
}
```

### 8.2 Saved methods contract (Optional)

**Why:** The host needs a stable way to save + query method metadata.

```php
namespace PayKit\Contracts;

use PayKit\Payload\Common\GatewayConfig;
use PayKit\Payload\Responses\SavedMethodList;
use PayKit\Payload\Requests\ListMethodsRequest;
use PayKit\Payload\Requests\GetMethodRequest;
use PayKit\Payload\Common\SavedMethod;
use PayKit\Payload\Requests\AttachMethodRequest;
use PayKit\Payload\Requests\DetachMethodRequest;
use PayKit\Payload\Requests\SetDefaultMethodRequest;

interface PaymentGatewaySavedMethodsContract
{
    public function listMethods(ListMethodsRequest $request, ?GatewayConfig $config = null): SavedMethodList;

    public function getMethod(GetMethodRequest $request, ?GatewayConfig $config = null): ?SavedMethod;

    public function attachMethod(AttachMethodRequest $request, ?GatewayConfig $config = null): SavedMethod;

    public function detachMethod(DetachMethodRequest $request, ?GatewayConfig $config = null): bool;

    public function setDefaultMethod(SetDefaultMethodRequest $request, ?GatewayConfig $config = null): bool;
}
```

---

## 9) Virtual accounts (deep support)

Virtual accounts can behave like always-on funding sources:

* deposits can happen anytime
* withdrawals may be supported
* the host must be able to fetch history
* the driver must “watch” deposits/withdrawals via webhook or polling

### 9.1 Virtual account provisioning

```php
namespace PayKit\Contracts;

use PayKit\Payload\Common\GatewayConfig;
use PayKit\Payload\Requests\VirtualAccountCreateRequest;
use PayKit\Payload\Responses\VirtualAccountRecord;
use PayKit\Payload\Requests\VirtualAccountGetRequest;
use PayKit\Payload\Requests\ListVirtualAccountsRequest;
use PayKit\Payload\Responses\VirtualAccountList;

interface PaymentGatewayVirtualAccountsContract
{
    public function createVirtualAccount(VirtualAccountCreateRequest $request, ?GatewayConfig $config = null): VirtualAccountRecord;

    public function getVirtualAccount(VirtualAccountGetRequest $request, ?GatewayConfig $config = null): ?VirtualAccountRecord;

    public function listVirtualAccounts(ListVirtualAccountsRequest $request, ?GatewayConfig $config = null): VirtualAccountList;
}
```

### 9.2 Ledger/history

```php
namespace PayKit\Contracts;

use PayKit\Payload\Common\GatewayConfig;
use PayKit\Payload\Requests\VirtualAccountLedgerQuery;
use PayKit\Payload\Responses\VirtualAccountLedgerPage;
use PayKit\Payload\Requests\VirtualAccountLedgerEntryQuery;
use PayKit\Payload\Responses\VirtualAccountLedgerEntry;

interface PaymentGatewayVirtualAccountLedgerContract
{
    public function getLedger(VirtualAccountLedgerQuery $query, ?GatewayConfig $config = null): VirtualAccountLedgerPage;

    public function getLedgerEntry(VirtualAccountLedgerEntryQuery $query, ?GatewayConfig $config = null): ?VirtualAccountLedgerEntry;
}
```

### 9.3 Withdrawals (Optional)

```php
namespace PayKit\Contracts;

use PayKit\Payload\Common\GatewayConfig;
use PayKit\Payload\Requests\VirtualAccountWithdrawalRequest;
use PayKit\Payload\Responses\VirtualAccountWithdrawalResult;
use PayKit\Payload\Requests\VirtualAccountWithdrawalVerifyRequest;
use PayKit\Payload\Responses\VirtualAccountWithdrawalStatusResult;

interface PaymentGatewayVirtualAccountWithdrawalsContract
{
    public function initiateWithdrawal(VirtualAccountWithdrawalRequest $request, ?GatewayConfig $config = null): VirtualAccountWithdrawalResult;

    public function verifyWithdrawal(VirtualAccountWithdrawalVerifyRequest $request, ?GatewayConfig $config = null): ?VirtualAccountWithdrawalStatusResult;
}
```

---

## 10) Virtual account watchers (enforcing “watching deposits/withdrawals”)

### Compliance rule

If a driver implements `PaymentGatewayVirtualAccountsContract`, it must implement **at least one** of:

* `PaymentGatewayVirtualAccountWebhookWatcherContract`
* `PaymentGatewayVirtualAccountPollingWatcherContract`

This ensures the host can reliably detect sudden deposits/withdrawals.

### 10.1 Webhook watcher (push)

```php
namespace PayKit\Contracts;

use PayKit\Payload\Common\GatewayConfig;
use PayKit\Payload\Requests\WebhookRequest;
use PayKit\Payload\Responses\WebhookVerifyResult;
use PayKit\Payload\Events\VirtualAccountEvent;

interface PaymentGatewayVirtualAccountWebhookWatcherContract
{
    public function verifyWebhook(WebhookRequest $request, ?GatewayConfig $config = null): WebhookVerifyResult;

    public function parseVirtualAccountEvent(WebhookRequest $request, ?GatewayConfig $config = null): VirtualAccountEvent;
}
```

### 10.2 Polling watcher (pull)

```php
namespace PayKit\Contracts;

use PayKit\Payload\Common\GatewayConfig;
use PayKit\Payload\Responses\PollSpec;
use PayKit\Payload\Requests\PollVirtualAccountEventsQuery;
use PayKit\Payload\Events\VirtualAccountEventBatch;

interface PaymentGatewayVirtualAccountPollingWatcherContract
{
    public function pollSpec(?GatewayConfig $config = null): PollSpec;

    public function pollVirtualAccountEvents(PollVirtualAccountEventsQuery $query, ?GatewayConfig $config = null): VirtualAccountEventBatch;
}
```

### 10.3 Virtual account reconciliation (repair/backfill)

```php
namespace PayKit\Contracts;

use PayKit\Payload\Common\GatewayConfig;
use PayKit\Payload\Requests\ReconcileQuery;
use PayKit\Payload\Responses\ReconcileResult;

interface PaymentGatewayVirtualAccountReconcileContract
{
    public function reconcileVirtualAccounts(ReconcileQuery $query, ?GatewayConfig $config = null): ReconcileResult;
}
```

---

## 11) Payouts (general withdrawals)

### 11.1 Payouts contract (Optional)

```php
namespace PayKit\Contracts;

use PayKit\Payload\Common\GatewayConfig;
use PayKit\Payload\Requests\PayoutRequest;
use PayKit\Payload\Responses\PayoutResult;
use PayKit\Payload\Requests\PayoutVerifyRequest;
use PayKit\Payload\Responses\PayoutStatusResult;

interface PaymentGatewayPayoutsContract
{
    public function initiatePayout(PayoutRequest $request, ?GatewayConfig $config = null): PayoutResult;

    public function verifyPayout(PayoutVerifyRequest $request, ?GatewayConfig $config = null): ?PayoutStatusResult;
}
```

### 11.2 Beneficiaries contract (Optional)

```php
namespace PayKit\Contracts;

use PayKit\Payload\Common\GatewayConfig;
use PayKit\Payload\Requests\BeneficiaryCreateRequest;
use PayKit\Payload\Requests\BeneficiaryUpdateRequest;
use PayKit\Payload\Responses\BeneficiaryList;

interface PaymentGatewayBeneficiariesContract
{
    public function listBeneficiaries(?GatewayConfig $config = null): BeneficiaryList;

    public function createBeneficiary(BeneficiaryCreateRequest $request, ?GatewayConfig $config = null): bool;

    public function updateBeneficiary(BeneficiaryUpdateRequest $request, ?GatewayConfig $config = null): bool;
}
```

---

## 12) Virtual cards (Optional)

> Payload details for virtual cards are intentionally minimal in this SDK version.
> Drivers can still implement the capability contract and use provider-specific internals.

```php
namespace PayKit\Contracts;

use PayKit\Payload\Common\GatewayConfig;

interface PaymentGatewayVirtualCardsContract
{
    public function isSupported(?GatewayConfig $config = null): bool;
}
```

---

## 13) Generic reconciliation & diagnostics

### 13.1 Generic reconcile contract (Optional)

```php
namespace PayKit\Contracts;

use PayKit\Payload\Common\GatewayConfig;
use PayKit\Payload\Requests\ReconcileQuery;
use PayKit\Payload\Responses\ReconcileResult;

interface PaymentGatewayReconcileContract
{
    public function reconcile(ReconcileQuery $query, ?GatewayConfig $config = null): ReconcileResult;
}
```

### 13.2 Diagnostics contract (Optional)

```php
namespace PayKit\Contracts;

use PayKit\Payload\Common\GatewayConfig;
use PayKit\Payload\Common\HealthCheckResult;

interface PaymentGatewayDiagnosticsContract
{
    public function diagnostics(?GatewayConfig $config = null): HealthCheckResult;
}
```

---

## 14) The Manager (host-side resolver)

The host uses a registry to map `driver_key` (from the DB model) to a driver class.

### Responsibilities

* Register installed drivers
* Resolve a driver instance using a `GatewayConfig`
* Optionally trigger manifest sync

> The SDK ships a `GatewayRegistry`, a `DriverResolver`, and a `GatewayManager`. The host can use these directly or via the `Pay` entrypoint.

### 14.1 Registry + Manager

```php
namespace PayKit\Manager;

use PayKit\Contracts\PaymentGatewayDriverContract;
use PayKit\Contracts\PaymentGatewayManifestProviderContract;
use PayKit\Exceptions\GatewayCapabilityException;
use PayKit\Exceptions\GatewayConfigException;
use PayKit\Payload\Common\GatewayConfig;
use PayKit\Payload\Common\GatewayManifest;

final readonly class GatewayManager
{
    public function __construct(private DriverResolver $resolver)
    {
    }

    public function make(string $driverKey, GatewayConfig $config, bool $validate = true): PaymentGatewayDriverContract
    {
        $driver = $this->resolver->resolve($driverKey, $config);

        if ($validate) {
            $res = $driver->validateConfig();
            if (property_exists($res, 'ok') && $res->ok === false) {
                /** @var array<string,mixed> $errors */
                $errors = method_exists($res, 'errors') ? $res->errors() : [];
                throw GatewayConfigException::invalid($driverKey, $errors);
            }
        }

        return $driver;
    }

    public function manifest(string $driverKey, GatewayConfig $config, bool $validate = true): GatewayManifest
    {
        $driver = $this->make($driverKey, $config, $validate);

        if (!$driver instanceof PaymentGatewayManifestProviderContract) {
            throw GatewayCapabilityException::notSupported($driverKey, PaymentGatewayManifestProviderContract::class, 'getManifest');
        }

        return $driver->getManifest();
    }
}
```

> Your host app can wrap `make()` with DB resolution (PaymentGateway row → driverKey + config).

### 14.2 `Pay` entrypoint (SDK facade)

If you prefer **one import** (and static access) instead of injecting `GatewayManager` everywhere, the SDK includes: `PayKit\Pay`.

It provides:

* `Pay::register($driverKey, DriverClass::class)`
* `Pay::driver($driverKey, GatewayConfig $config, bool $validate = true)`
* `Pay::via($source, bool $validate = true)` — resolve from a host adapter (DB model, config object, etc.)

#### Host adapter contract

```php
namespace PayKit\Contracts;

use PayKit\Payload\Common\GatewayConfig;

interface ProvidesGatewayConfigContract
{
    public function gatewayDriverKey(): string;

    public function gatewayConfig(): GatewayConfig;
}
```

#### Example `Pay` usage

Register drivers once (Laravel `ServiceProvider`, bootstrap file, etc.):

```php
use PayKit\Pay;

Pay::register('stripe', \App\Payments\Drivers\StripeDriver::class);
Pay::register('paystack', \App\Payments\Drivers\PaystackDriver::class);
```

Resolve from a DB model (recommended DX): your model implements `ProvidesGatewayConfigContract`.

```php
use PayKit\Pay;

$driver = Pay::via($gatewayModel);
$result = $driver->initiatePayment($payload);
```

Manual resolution (tests / scripts):

```php
use PayKit\Pay;
use PayKit\Payload\Common\GatewayConfig;

$driver = Pay::driver('stripe', new GatewayConfig(secrets: ['secret_key' => '...'], options: []));
```

---

## 15) Host flows (how it works end‑to‑end)

### Flow A — Install/Enable gateway (manifest sync)

1. Host resolves driver and loads `GatewayConfig` from DB.
2. Host calls `getManifest()`.
3. Host persists:

    * supported currencies/countries
    * feature flags
    * UI modules
    * requirements

✅ Runtime filtering now uses DB, not driver calls.

> Sync-time discovery is allowed: drivers may call provider APIs **inside** `getManifest()` to discover currencies/countries/features — **only** during sync.

### Flow B — Render checkout page (scripts/UI)

* Host queries enabled gateways from DB.
* Host collects scripts via `PaymentGatewayScriptsContract`.
* Host renders gateway options; if UI modules exist, host mounts them.

### Flow C — Initiate payment

1. Host builds `PaymentInitiateRequest(reference, money, meta, ...)`.
2. Host calls `initiatePayment()`.
3. Host handles `NextAction`:

    * redirect user
    * render inline widget
    * show manual instructions

### Flow D — Confirm payment

* Webhook:

    1. `verifyWebhook`
    2. `parseWebhook` → `WebhookEvent`
    3. host updates its own state

* Verify polling:

    1. `verifyPayment`
    2. host updates its own state

### Flow E — Virtual accounts “watching deposits/withdrawals”

* Webhook watcher and/or polling watcher produces `VirtualAccountEvent`.
* Host persists events and updates balances.
* Withdrawals (if supported) are initiated via withdrawals contract.

---

## 16) Security notes

* Secrets must live only in `GatewayConfig::$secrets` (host encrypted storage).
* `publicConfig()` must never return secrets.
* `redactForLogs()` must scrub any payloads before logging.
* Saved-method payloads must never contain PAN/CVV.

---

## 17) Watchouts (implementation risks)

### 17.1 DTO serialization

If you pass payloads/DTOs through queues, ensure they are easily serializable (no closures, stream handles, or other non-serializable resources).

### 17.2 UI manifest complexity

UI manifests require a host-side mapping registry:

* Backend returns module descriptors (`id`, `type`, `entry`, `props`).
* Frontend maps `entry` → a real component.

Keep the contract simple: prefer “component key + props”, not backend-driven UI layout.

### 17.3 Versioning payloads

* Field rename/removal = major.
* Adding optional fields = minor.

When adding fields, prefer nullable defaults to avoid breaking older drivers.

---

## 18) Versioning

* Semantic versioning.
* Payload field rename/removal = major.
* Adding optional fields = minor.

---

## 19) Suggested driver base classes

* `AbstractPaymentGatewayDriver`

    * accepts an optional default `GatewayConfig`
    * resolves config via hybrid rule (method override > default > error)
    * provides driver helpers and shared concerns

* Manifest helpers (via traits/concerns)

    * builders for `GatewayManifest`
    * schema helpers for `GatewayConfigSchema`

---

## 20) What’s next

1. Decide which contracts are “Core” for v1.
2. Decide whether actions are strict classes (`NextAction`) vs a tagged union.
3. Decide host persistence shape for manifests (JSON vs pivots).

Once those are locked, generate stubs for drivers and publish the SDK.
