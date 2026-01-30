# 📦 PayKit Gateway SDK (Contracts + Payloads)

A **business‑agnostic Payment Gateway SDK** that lets a host application integrate multiple payment providers through
strict contracts and typed payloads.

✅ What we ship:

* **Contracts (interfaces):** define what a gateway driver can do (payments, refunds, saved cards, virtual accounts,
  payouts, etc.)
* **Payloads (DTOs):** strict request/response/event shapes (no magic arrays)
* **Abstract driver base(s):** safe defaults + reusable helpers
* **Manager/Registry:** resolve drivers by `driver_key`
* **Frontend primitives:** scripts + UI module manifests (framework‑agnostic)

❌ What we do **not** ship:

* Checkout/cart/invoice semantics (the SDK never cares what the payment is for)
* Host database schema or migrations (hosts persist manifests/capabilities however they want)
* A UI framework implementation (React/Vue/etc. are host concerns)

> Source of truth: only `src/` is the SDK public surface.

---

## 0) Installation

### Composer

```bash
composer require timeax/paykit-sdk
```

### Namespace / Autoloading

This SDK is PSR-4 autoloaded under the `PayKit\` namespace (your package `composer.json` should use):

```json
"autoload": {
  "psr-4": {
    "PayKit\\": "src/"
  }
}
```

---

## 0.1) Laravel integration (manual, copy-paste)

PayKit ships as a plain Composer library (framework-agnostic). In Laravel, you can bind the manager as a singleton in
your **host app**.

### Option A — Bind only `GatewayManager` (simplest host DX)

```php
// app/Providers/AppServiceProvider.php

use PayKit\Manager\GatewayManager;

public function register(): void
{
    $this->app->singleton(GatewayManager::class, fn () => new GatewayManager());
}
```

### Option B — Bind the full manager stack (explicit singletons)

```php
// app/Providers/AppServiceProvider.php

use PayKit\Manager\GatewayManager;
use PayKit\Manager\GatewayRegistry;
use PayKit\Manager\DriverResolver;

public function register(): void
{
    $this->app->singleton(GatewayRegistry::class, fn () => new GatewayRegistry());

    $this->app->singleton(DriverResolver::class, fn ($app) =>
        new DriverResolver($app->make(GatewayRegistry::class))
    );

    $this->app->singleton(GatewayManager::class, fn ($app) =>
        new GatewayManager(
            $app->make(GatewayRegistry::class),
            $app->make(DriverResolver::class),
        )
    );
}
```

> If your constructor signatures differ, adjust the bindings accordingly — the intent is “one registry + one resolver +
> one manager per app”.

---

## 1) Core philosophy

### 1. Model‑First (Host DB is Source of Truth)

Drivers may discover capabilities (currencies/countries/features) **only during manifest sync**, not at checkout
runtime.

### 2. Host‑Controlled Business Effects

* Drivers only talk to providers.
* Hosts decide what “paid” means (credit wallet, place orders, grant access, etc.).

### 3. Strict Contracts + Payloads

Everything is typed. No random associative arrays for core flows.

### 4. Capability‑by‑Contract

New features are optional contracts, not scattered flags.

---

## 2) What this SDK solves

* A single integration surface across many providers.
* Normalized statuses (canonical sets).
* A manifest/capability engine so checkout can be DB‑filtered without calling provider APIs.
* Optional frontend integration metadata (scripts + UI module keys).

---

## 3) Glossary

* **Driver:** Provider implementation (Stripe, Paystack, Flutterwave, etc.).
* **Manifest:** Driver’s support/capability snapshot (currencies, countries, features).
* **Support Matrix:** What the driver supports (where/what currencies/countries).
* **Feature Set:** What the driver can do (refunds, saved cards, virtual accounts, payouts, etc.).
* **UI Manifest:** Framework‑agnostic frontend module descriptors.

---

## 4) Config injection (Hybrid)

PayKit uses a **hybrid config injection** pattern:

* Drivers may receive a **default config** in the constructor (standard “manager” flow).
* Contract methods accept an **optional override config**: `?ConfigBag $config = null`.
* Resolution rule: **method override > constructor default > error**.

This keeps host code clean in normal flows, while allowing stateless/batch use when needed (e.g., iterating through
multiple merchant accounts).

**Rule:** Wherever a method takes both a config and other parameters, the config **must be the last parameter**.

Config is carried in `Timeax\ConfigSchema\Support\ConfigBag` (from `timeax/ui-config-schema`), which holds `options`
and `secrets` and provides `option()` / `secret()` helpers.

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

    # --- saved methods + card tokenization ---
    PaymentGatewaySavedMethodsContract.php
    PaymentGatewayCardTokenizationContract.php

    # --- refunds + disputes ---
    PaymentGatewayRefundsContract.php
    PaymentGatewayDisputesContract.php

    # --- virtual accounts ---
    PaymentGatewayVirtualAccountsContract.php
    PaymentGatewayVirtualAccountLedgerContract.php
    PaymentGatewayVirtualAccountWithdrawalsContract.php
    PaymentGatewayVirtualAccountWebhookWatcherContract.php
    PaymentGatewayVirtualAccountPollingWatcherContract.php
    PaymentGatewayVirtualAccountReconcileContract.php

    # --- payouts ---
    PaymentGatewayPayoutsContract.php
    PaymentGatewayBeneficiariesContract.php

    # --- virtual cards ---
    PaymentGatewayVirtualCardsContract.php

    # --- reconciliation + diagnostics ---
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

      # config + schema (from timeax/ui-config-schema)
      # ConfigBag, ConfigSchema, ConfigField, ConfigValidationResult (external)
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
      HasConfigSchema.php
      ResolvesConfig.php
      MapsStatuses.php
      RedactsSecrets.php
      BuildsManifest.php

  Manager/
    GatewayRegistry.php
    DriverResolver.php
    GatewayManager.php

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

Your driver must normalize provider statuses to a closed set.

---

## 7) Contracts (Interfaces)

### 7.1 Base driver contract (Required)

Defines the minimum every driver must support: identity, config schema, validation, health check, and redaction.

### 7.2 Manifest provider contract (Strongly recommended)

Allows hosts to sync a snapshot of gateway capabilities for DB‑first checkout filtering.

### 7.3 Availability contract (Optional)

Allow hosts to cheaply hide gateways based on lightweight context (country, user type, etc.).

---

## 8) Frontend integration

### Scripts

Drivers may provide `GatewayScript[]` (tags, URLs, placement) for the host to inject.

### UI manifest

Drivers may provide module keys (host maps keys → actual components).

---

## 9) Saved methods and card tokenization

Saved methods and tokenization are optional capability contracts.

---

## 10) Virtual accounts (correctness requirement)

If a driver supports virtual accounts (`PaymentGatewayVirtualAccountsContract`), it must implement at least one watcher:

* `PaymentGatewayVirtualAccountWebhookWatcherContract` **or**
* `PaymentGatewayVirtualAccountPollingWatcherContract`

Optionally implement reconciliation.

---

## 11) Payouts (general withdrawals)

Optional contracts: payouts + beneficiaries.

---

## 12) Virtual cards (Optional)

Optional contract.

---

## 13) Generic reconciliation & diagnostics

Optional contracts.

---

## 14) The Manager (host-side resolver)

The host uses a registry to map `driver_key` (from the DB model) to a driver class.

### Responsibilities

* Register installed drivers
* Resolve a driver instance using a `ConfigBag`
* Optionally trigger manifest sync

```php
namespace PayKit\Manager;

use PayKit\Contracts\PaymentGatewayDriverContract;
use Timeax\ConfigSchema\Support\ConfigBag;

...
```

> Your host app can wrap `make()` with DB resolution (PaymentGateway row → driverKey + config).

### Optional: `Pay` entrypoint (SDK facade)

If you prefer **one import** (and static access) instead of injecting `GatewayManager` everywhere, the SDK includes a
small entrypoint: `PayKit\Pay`.

It wraps a singleton `GatewayManager`/`GatewayRegistry` and provides:

* `Pay::register($driverKey, DriverClass::class, ...)`
* `Pay::registerGateway(GatewayRegistration $reg)`
* `Pay::setProvider($providerClass)`
* `Pay::driver($driverKey, ConfigBag $config)`
* `Pay::via($source)`
* `Pay::list($filter)`

#### Host adapter contract

```php
namespace PayKit\Contracts;

use Timeax\ConfigSchema\Support\ConfigBag;

interface ProvidesGatewayConfigContract
{
    public function gatewayDriverKey(): string;

    public function gatewayConfig(): ConfigBag;
}
```

#### Usage (host)

**Register drivers once** (Laravel ServiceProvider, bootstrap file, etc.):

```php
use PayKit\Pay;

// 1. Basic registration
Pay::register('stripe', \App\Payments\Drivers\StripeDriver::class);

// 2. Register with a concrete gateway ID (enables Pay::via(12))
Pay::register(
    'paystack',
    \App\Payments\Drivers\PaystackDriver::class,
    gatewayId: 12,
    providerClass: \App\Models\PaymentGateway::class
);

// 3. Set a default provider class for all gateway IDs
Pay::setProvider(\App\Models\PaymentGateway::class);
Pay::register('flutterwave', \App\Payments\Drivers\FlutterwaveDriver::class, gatewayId: 'fw_001');

// 4. Register a gateway entry manually (if driver is already registered)
use PayKit\Payload\Common\GatewayRegistration;

Pay::registerGateway(new GatewayRegistration(
    gatewayId: 'fw_002',
    driverKey: 'flutterwave',
    providerClass: \App\Models\PaymentGateway::class
));
```

**Resolve driver** (recommended DX):

```php
use PayKit\Pay;

// A) From a DB model (implements ProvidesGatewayConfigContract)
// Returns PaymentGatewayPayDriverContract (asserts pay capability)
$driver = Pay::via($gatewayModel);

// B) From a gateway ID (requires registration with gatewayId)
$driver = Pay::via(12);
$driver = Pay::via('fw_001');

// C) From driver key + config
$driver = Pay::via('stripe', $config);

// Use the driver
$result = $driver->initiatePayment($payload);
```

**List available gateways**:

```php
use PayKit\Pay;
use PayKit\Payload\Requests\GatewayListFilter;
use PayKit\Payload\Common\Currency;
use PayKit\Payload\Common\Country;

// Filter by currency, country, or features
$list = Pay::list(new GatewayListFilter(
    currency: new Currency('USD'),
    country: new Country('US')
), includeDriversWithoutGateways: false);
```

**Manual resolution** (tests / scripts):

```php
use PayKit\Pay;
use Timeax\ConfigSchema\Support\ConfigBag;

// Returns PaymentGatewayDriverContract (no capability assertion)
$driver = Pay::driver('stripe', new ConfigBag(
    secrets: ['secret_key' => '...'],
    options: ['environment' => 'test'],
));
```

---

## 15) Host flows (how it works end‑to‑end)

### Flow A — Install/Enable gateway (manifest sync)

1. Host resolves driver and loads `ConfigBag` from DB.
2. Host calls `getManifest()`.
3. Host persists:

* supported currencies/countries
* feature flags (by contract)
* any UI metadata

### Flow B — Render checkout page (scripts/UI)

1. Host filters gateways using the stored manifest.
2. Host injects scripts (if any).
3. Host maps `UiManifest` keys to frontend components.

### Flow C — Initiate payment

1. Host creates `PaymentInitiateRequest`.
2. Host calls `initiatePayment()`.
3. Host renders the `NextAction`.

### Flow D — Verify / Webhook

1. Host calls `verifyPayment()` (pull) **or** validates and parses webhook (push).
2. Host applies business effect (credit wallet, create orders, etc.).

---

## 16) Implementation samples (Stripe)

This section shows a practical implementation pattern using a `StripeDriver` as the subject driver.

### 16.1 Minimal host adapter (DB model)

Your host gateway record should implement `ProvidesGatewayConfigContract`.

```php
namespace App\Models;

use PayKit\Contracts\ProvidesGatewayConfigContract;
use Timeax\ConfigSchema\Support\ConfigBag;

final class PaymentGateway /* extends Model */ implements ProvidesGatewayConfigContract
{
    public string $driver_key;

    /** @var array<string,mixed> */
    public array $secrets = [];

    /** @var array<string,mixed> */
    public array $options = [];

    public function gatewayDriverKey(): string
    {
        return $this->driver_key;
    }

    public function gatewayConfig(): ConfigBag
    {
        // secrets should come from encrypted storage in the real host.
        return new ConfigBag(secrets: $this->secrets, options: $this->options);
    }
}
```

### 16.2 Stripe config schema

A typical Stripe gateway needs:

* `secret_key` (required)
* `publishable_key` (required)
* `webhook_secret` (optional unless webhooks are enabled)

```php
namespace App\Payments\Drivers;

use PayKit\Drivers\AbstractPaymentGatewayDriver;
use PayKit\Drivers\Concerns\HasConfigSchema;
use PayKit\Drivers\Concerns\ResolvesConfig;
use Timeax\ConfigSchema\Schema\ConfigField;
use Timeax\ConfigSchema\Support\ConfigBag;
use Timeax\ConfigSchema\Support\ConfigValidationResult;

final class StripeDriver extends AbstractPaymentGatewayDriver
{
    use ResolvesConfig;
    use HasConfigSchema;

    public function driverKey(): string
    {
        return 'stripe';
    }

    /** @return array<int,ConfigField> */
    protected function configFields(): array
    {
        return [
            new ConfigField(
                name: 'secret_key',
                label: 'Stripe Secret Key',
                required: true,
                secret: true,
            ),
            new ConfigField(
                name: 'publishable_key',
                label: 'Stripe Publishable Key',
                required: true,
            ),
            new ConfigField(
                name: 'webhook_secret',
                label: 'Webhook Signing Secret',
                required: false,
                secret: true,
            ),
        ];
    }

    public function validateConfig(?ConfigBag $config = null): ConfigValidationResult
    {
        $cfg = $this->resolveConfig($config);
        return $this->configSchema()->validate($cfg);
    }
}
```

> Notes:
>
> * `HasConfigSchema::validateConfig(...)` uses `resolveConfig(...)` if available and validates required fields.
> * Config values live in `ConfigBag` `secrets` / `options` — the driver reads from the resolved config.

### 16.3 Stripe health check (required)

PayKit requires drivers to implement their own health check (no default stub).

```php
use Timeax\ConfigSchema\Support\ConfigBag;
use PayKit\Payload\Common\HealthCheckResult;

final class StripeDriver extends AbstractPaymentGatewayDriver
{
    // ... config schema above

    public function healthCheck(?ConfigBag $config = null): HealthCheckResult
    {
        $cfg = $this->resolveConfig($config);

        // Pseudo-check: confirm we have the required keys.
        // Real driver might do a lightweight API call (e.g. retrieve account / balance).
        $secret = $cfg->secret('secret_key');

        if (!$secret) {
            return HealthCheckResult::fail('Missing secret_key');
        }

        return HealthCheckResult::ok('Stripe config looks valid');
    }
}
```

### 16.4 Initiate payment (NextAction)

Most Stripe flows return an inline action using a client secret.

```php
namespace App\Payments\Drivers;

use PayKit\Contracts\PaymentGatewayPaymentsContract;
use Timeax\ConfigSchema\Support\ConfigBag;
use PayKit\Payload\Common\CanonicalPaymentStatus;
use PayKit\Payload\Requests\PaymentInitiateRequest;
use PayKit\Payload\Responses\InlineAction;
use PayKit\Payload\Responses\NextAction;
use PayKit\Payload\Responses\PaymentInitiateResult;

final class StripeDriver extends AbstractPaymentGatewayDriver implements PaymentGatewayPaymentsContract
{
    // ... traits + config schema + healthCheck

    public function initiatePayment(PaymentInitiateRequest $request, ?ConfigBag $config = null): PaymentInitiateResult
    {
        $cfg = $this->resolveConfig($config);

        // Pseudo: create a PaymentIntent and return client secret.
        // $clientSecret = $this->stripe($cfg)->paymentIntents->create([...])->client_secret;
        $clientSecret = 'pi_xxx_secret_yyy';

        $action = new InlineAction(
            entry: 'stripe.checkout',
            props: [
                'publishableKey' => $cfg->secret('publishable_key'),
                'clientSecret' => $clientSecret,
                'reference' => (string) $request->reference,
            ],
        );

        return new PaymentInitiateResult(
            reference: $request->reference,
            providerRef: null,
            action: new NextAction($action),
            raw: null,
        );
    }

    public function mapStatus(mixed $rawPayload): CanonicalPaymentStatus
    {
        // Pseudo mapping. A real driver maps provider status fields to canonical enum.
        return CanonicalPaymentStatus::processing;
    }
}
```

### 16.5 Scripts + UI manifest

Stripe typically needs `https://js.stripe.com/v3/` on checkout pages.

```php
use PayKit\Contracts\PaymentGatewayScriptsContract;
use Timeax\ConfigSchema\Support\ConfigBag;
use PayKit\Payload\Common\GatewayScript;
use PayKit\Payload\Common\ScriptLocation;

final class StripeDriver extends AbstractPaymentGatewayDriver implements PaymentGatewayScriptsContract
{
    public function getScripts(?ConfigBag $config = null): array
    {
        return [
            new GatewayScript(
                src: 'https://js.stripe.com/v3/',
                location: ScriptLocation::head,
                async: true,
            ),
        ];
    }
}
```

UI modules are host-mapped keys (the SDK never ships React/Vue components).

```php
use PayKit\Contracts\PaymentGatewayUiContract;
use PayKit\Payload\Common\UiEntryDescriptor;
use PayKit\Payload\Common\UiManifest;
use PayKit\Payload\Common\UiModuleDescriptor;

final class StripeDriver extends AbstractPaymentGatewayDriver implements PaymentGatewayUiContract
{
    public function uiManifest(): UiManifest
    {
        return new UiManifest([
            new UiModuleDescriptor(
                id: 'stripe.settings',
                entry: new UiEntryDescriptor(key: 'stripe.settings'),
            ),
            new UiModuleDescriptor(
                id: 'stripe.checkout',
                entry: new UiEntryDescriptor(key: 'stripe.checkout'),
            ),
        ]);
    }
}
```

### 16.6 Webhook verification + parsing

If implementing `PaymentGatewayWebhooksContract`, the driver must verify signature and parse to a normalized event
payload.

```php
use PayKit\Contracts\PaymentGatewayWebhooksContract;
use Timeax\ConfigSchema\Support\ConfigBag;
use PayKit\Payload\Events\WebhookEvent;
use PayKit\Payload\Requests\WebhookRequest;
use PayKit\Payload\Responses\WebhookVerifyResult;

final class StripeDriver extends AbstractPaymentGatewayDriver implements PaymentGatewayWebhooksContract
{
    public function verifyWebhook(WebhookRequest $request, ?ConfigBag $config = null): WebhookVerifyResult
    {
        $cfg = $this->resolveConfig($config);

        // Pseudo signature validation. Real driver uses Stripe signature header + webhook secret.
        $secret = $cfg->secret('webhook_secret');
        if (!$secret) {
            return WebhookVerifyResult::fail('Missing webhook secret');
        }

        return WebhookVerifyResult::ok();
    }

    public function parseWebhook(WebhookRequest $request, ?ConfigBag $config = null): WebhookEvent
    {
        // Pseudo parse. Real driver decodes JSON and normalizes event type + provider refs.
        return new WebhookEvent(
            type: 'payment.succeeded',
            reference: null,
            providerRef: null,
            status: null,
            raw: $request->payload,
        );
    }
}
```

### 16.7 Stripe manifest (sync-time discovery)

The host calls this during install/enable/config update and persists the result.

```php
use PayKit\Contracts\PaymentGatewayManifestProviderContract;
use Timeax\ConfigSchema\Support\ConfigBag;
use PayKit\Payload\Common\GatewayFeatureSet;
use PayKit\Payload\Common\GatewayManifest;
use PayKit\Payload\Common\GatewaySupportMatrix;

final class StripeDriver extends AbstractPaymentGatewayDriver implements PaymentGatewayManifestProviderContract
{
    public function getManifest(?ConfigBag $config = null): GatewayManifest
    {
        // Allowed to call provider APIs here if needed (sync-time only).
        // For sample: hard-coded.

        $features = new GatewayFeatureSet(
            payments: true,
            refunds: true,
            savedMethods: true,
            webhooks: true,
            payouts: false,
            virtualAccounts: false,
        );

        $support = GatewaySupportMatrix::fromSimple(
            countries: ['US', 'GB', 'NG'],
            currencies: ['USD', 'GBP', 'NGN'],
        );

        return new GatewayManifest(
            driverKey: $this->driverKey(),
            features: $features,
            support: $support,
            ui: $this->uiManifest(),
            scripts: $this->getScripts(),
        );
    }
}
```

---

## 17) Watchouts (implementation risks)

### A) Payload serialization

* No closures, resources, SDK client objects, or open streams.
* Prefer scalar/array/payload-only fields.
* Keep raw provider payloads as `array|string|null`.

### B) UI manifest complexity

* Backend returns module/component keys (+ optional props/schema)
* Host frontend owns a mapping registry (`"stripe.settings" -> <StripeSettings />`)
* Avoid backend-driven UI layout instructions.

### C) Versioning payloads

* Adding optional fields: **minor**.
* Renaming/removing/changing meaning: **major**.
* Prefer nullable additions to preserve driver compatibility.
