# 📦 PayKit Gateway SDK (Contracts + Payloads)

[![PHP Version](https://img.shields.io/badge/php-%5E8.2-blue.svg)](https://packagist.org/packages/timeax/paykit-sdk)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)
[![Build Status](https://img.shields.io/badge/tests-passing-brightgreen.svg)](tests/)
[![Static Analysis](https://img.shields.io/badge/phpstan-level%200%20clean-brightgreen.svg)](phpstan.neon)

A **business-agnostic, enterprise-grade Financial Infrastructure Protocol Engine** that enables host PHP applications to integrate multiple payment and banking providers (Stripe, Adyen, Paystack, Flutterwave, Airwallex, Marqeta) through strict contracts and strongly-typed payloads.

---

## 🎯 What We Ship

* **Strict Contracts (interfaces):** 39 granular PHP interfaces defining payment, refund, payout, bulk transfer, virtual account, double-entry ledger, balance snapshot, virtual card issuing, and webhook deduplication capabilities.
* **Typed Payloads (DTOs):** Immutable PHP 8.2+ request, response, and event objects (zero untyped arrays for core domain operations).
* **Elqora Interaction Protocol Integration:** Standardized frontend-host UI flow descriptions via [`elqora/interactions`](https://github.com/elqora/interactions).
* **Manager & Capability Engine:** DB-first driver registry and manifest resolution system for high-performance runtime filtering.
* **Abstract Driver Bases:** Safe defaults, reusable configuration helpers, and schema validation tools.

---

## ❌ What We Do NOT Ship

* Checkout/cart/invoice semantics (the SDK is strictly decoupled from host business domains).
* Host database schemas or migrations (hosts persist manifests and capabilities however they choose).
* Vendor-specific HTTP clients inside `src/` (drivers implement contracts using Guzzle, Symfony HTTP, or custom SDKs).

> **Source of Truth:** Only `src/` is the SDK public API surface.

---

## 0) Installation & Requirements

### PHP Version
Requires **PHP 8.2+** with `ext-json` and `ext-mbstring`.

### Composer
```bash
composer require timeax/paykit-sdk
```

### Namespace / Autoloading
This SDK is PSR-4 autoloaded under the `PayKit\` namespace:

```json
"autoload": {
  "psr-4": {
    "PayKit\\": "src/"
  }
}
```

---

## 0.1) Laravel Integration (Service Provider Binding)

PayKit is framework-agnostic. In Laravel host applications, bind `GatewayManager` as a singleton in your `AppServiceProvider`:

```php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use PayKit\Manager\DriverResolver;
use PayKit\Manager\GatewayManager;
use PayKit\Manager\GatewayRegistry;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(GatewayRegistry::class, fn () => new GatewayRegistry());

        $this->app->singleton(DriverResolver::class, fn ($app) =>
            new DriverResolver($app->make(GatewayRegistry::class))
        );

        $this->app->singleton(GatewayManager::class, fn ($app) =>
            new GatewayManager($app->make(DriverResolver::class))
        );
    }
}
```

---

## 1) Core Architectural Principles

### 1. Model-First (Host DB is Source of Truth)
Drivers discover support matrices (currencies, countries, capability flags) **only during manifest synchronization**, never at checkout runtime. Checkout filtering occurs in local database space without triggering high-latency third-party HTTP calls.

### 2. Host-Controlled Business Logic
Drivers communicate with payment vendors and report canonical statuses. The host application decides what "succeeded" means (e.g., crediting a user wallet, fulfilling an order, or releasing a digital asset).

### 3. Capability-by-Contract
New capabilities are introduced as discrete, isolated PHP interfaces in [`src/Contracts/`](file:///d:/Projects/GitHub/payment-gateway-sdk/src/Contracts). Feature support is verified via standard PHP runtime introspection (`$driver instanceof PaymentGatewayBulkPayoutsContract`).

### 4. Strict Typed Payloads & Idempotency
Core domain methods accept strongly-typed DTOs with explicit `IdempotencyKey` value objects and `GatewayFailure` error payloads.

---

## 2) Code Examples by Financial Domain

---

### 2.1 Core Payments & Elqora Interactions

Initiate a payment and receive a canonical status along with an **Elqora Interaction** (`Redirect`, `Instructions`, `QrCode`, `Component`, `Mount`, `Script`):

```php
use PayKit\Pay;
use PayKit\Payload\Common\Amount;
use PayKit\Payload\Common\Currency;
use PayKit\Payload\Common\Money;
use PayKit\Payload\Common\Reference;
use PayKit\Payload\Requests\PaymentInitiateRequest;

// Resolve driver singleton or via manager
$driver = Pay::via('stripe', $configBag);

$request = new PaymentInitiateRequest(
    reference: new Reference('order_9901'),
    money: new Money(Amount::from(5000), new Currency('USD')),
    customerEmail: 'customer@example.com',
    customerName: 'Jane Doe'
);

$result = $driver->initiatePayment($request);

if ($result->status->value === 'pending' && $result->interaction !== null) {
    // Interaction automatically serializes to canonical flat wire format for frontend execution
    $wirePayload = $result->interaction->toArray();
    /*
    [
        'type' => 'redirect',
        'url' => 'https://checkout.stripe.com/pay/cs_test_123',
        'target' => '_self'
    ]
    */
}
```

---

### 2.2 Outward Payouts, Sources & Typed Destinations

Route single payouts to bank accounts, mobile money wallets, cards, crypto addresses, or beneficiaries:

```php
use PayKit\Payload\Common\BankAccountDestination;
use PayKit\Payload\Common\IdempotencyKey;
use PayKit\Payload\Common\Money;
use PayKit\Payload\Common\PayoutDestination;
use PayKit\Payload\Common\PayoutSource;
use PayKit\Payload\Common\PayoutSourceType;
use PayKit\Payload\Common\Reference;
use PayKit\Payload\Requests\PayoutRequest;

// 1. Create a strongly-typed destination payload
$bankDestination = new PayoutDestination(
    payload: new BankAccountDestination(
        accountNumber: '0123456789',
        accountName: 'John Doe',
        bankCode: '058',
        routingNumber: '123456',
        providerData: ['nibss_code' => '000013']
    )
);

// 2. Define explicit payout source (Virtual Account, Provider Balance, or Balance Account)
$source = new PayoutSource(
    type: PayoutSourceType::virtual_account,
    sourceId: 'va_987654'
);

// 3. Initiate Payout with explicit IdempotencyKey
$payoutRequest = new PayoutRequest(
    reference: new Reference('payout_7711'),
    money: Money::from(25000, 'NGN'),
    destination: $bankDestination,
    source: $source,
    idempotencyKey: new IdempotencyKey('idemp_po_7711'),
    narration: 'Monthly vendor settlement'
);

$payoutResult = $driver->initiatePayout($payoutRequest);

echo $payoutResult->status->value; // "processing" | "succeeded" | "requires_action"
echo $payoutResult->netAmount?->toArray()['amount']; // Net settled funds
```

---

### 2.3 Bulk Payouts & Batch Transfers

Submit thousands of itemized payouts in a single batch with item-level status tracking:

```php
use PayKit\Payload\Common\BulkPayoutItem;
use PayKit\Payload\Common\MobileMoneyDestination;
use PayKit\Payload\Common\Money;
use PayKit\Payload\Common\PayoutDestination;
use PayKit\Payload\Common\Reference;
use PayKit\Payload\Requests\BulkPayoutRequest;

$item1 = new BulkPayoutItem(
    itemId: 'item_01',
    money: Money::from(1500, 'GHS'),
    destination: (new PayoutDestination(
        payload: new MobileMoneyDestination(phoneNumber: '+233241234567', operator: 'mtn')
    ))->payload
);

$bulkRequest = new BulkPayoutRequest(
    reference: new Reference('batch_2026_07'),
    items: [$item1],
    title: 'Payroll Batch'
);

$bulkResult = $driver->initiateBulkPayout($bulkRequest);

echo $bulkResult->totalCount;   // Total items in batch
echo $bulkResult->successCount; // Successfully processed items
```

---

### 2.4 Account Resolution & Destination Validation

Verify bank account numbers, IBANs, or mobile money names before payout execution:

```php
use PayKit\Payload\Common\BankAccountDestination;
use PayKit\Payload\Requests\PayoutDestinationResolveRequest;

$resolveRequest = new PayoutDestinationResolveRequest(
    destination: new BankAccountDestination(
        accountNumber: '0123456789',
        bankCode: '058'
    )
);

$resolution = $driver->resolvePayoutDestination($resolveRequest);

if ($resolution->resolved) {
    echo $resolution->accountName; // "Verified Account Name"
}
```

---

### 2.5 Virtual Accounts & Compliance Identity (KYC / KYB)

Provision dedicated collection accounts or single-use payment vIBANs with structured compliance identifiers:

```php
use PayKit\Payload\Common\Country;
use PayKit\Payload\Common\CustomerIdentity;
use PayKit\Payload\Common\IdentityIdentifier;
use PayKit\Payload\Common\Reference;
use PayKit\Payload\Common\VirtualAccountPurpose;
use PayKit\Payload\Common\VirtualAccountUsage;
use PayKit\Payload\Requests\VirtualAccountCreateRequest;

$customer = new CustomerIdentity(
    providerCustomerId: 'cus_8899',
    name: 'Alice Smith',
    email: 'alice@example.com',
    phone: '+2348012345678',
    identifiers: [
        new IdentityIdentifier(type: 'bvn', value: '22113344556', country: new Country('NG')),
        new IdentityIdentifier(type: 'nin', value: '11223344556', country: new Country('NG')),
    ]
);

$vaRequest = new VirtualAccountCreateRequest(
    reference: new Reference('va_req_100'),
    ownerKey: 'usr_4455',
    purpose: VirtualAccountPurpose::collection,
    usage: VirtualAccountUsage::reusable,
    customer: $customer
);

$provisionResult = $driver->createVirtualAccount($vaRequest);
echo $provisionResult->status->value; // "active" | "pending"
```

---

### 2.6 Balances, Double-Entry Ledgers & Internal Transfers

Inspect balance snapshots, query double-entry accounting transactions, or move funds internally:

```php
use PayKit\Payload\Requests\LedgerQuery;
use PayKit\Payload\Requests\TransferRequest;

// 1. Get Balance Snapshot
$balance = $driver->getBalance('bal_account_01');
echo $balance->balances->available->toArray()['amount']; // Cleared funds

// 2. Query Accounting Ledger Page
$ledgerPage = $driver->getLedger(new LedgerQuery(accountId: 'bal_account_01', limit: 20));

foreach ($ledgerPage->items as $transaction) {
    echo $transaction->direction->value; // "credit" | "debit"
    echo $transaction->type->value;      // "payment", "payout", "fee", "transfer"
    echo $transaction->bookedAt;         // Booking ISO timestamp
}

// 3. Internal Account Movement (No outward banking fees)
$transferResult = $driver->transfer(new TransferRequest(
    reference: new Reference('xfer_3322'),
    sourceAccountId: 'bal_01',
    destinationAccountId: 'bal_02',
    money: Money::from(500, 'USD')
));
```

---

### 2.7 Modular Virtual Cards & Spending Controls

Provision virtual cards, update velocity/MCC controls, process JIT webhook authorizations, or request PCI-isolated card reveal sessions:

```php
use PayKit\Payload\Common\CardControls;
use PayKit\Payload\Common\Currency;
use PayKit\Payload\Common\Money;
use PayKit\Payload\Common\Reference;
use PayKit\Payload\Requests\VirtualCardControlsRequest;
use PayKit\Payload\Requests\VirtualCardCreateRequest;

// 1. Issue Virtual Card
$card = $driver->createCard(new VirtualCardCreateRequest(
    reference: new Reference('card_req_01'),
    cardholderName: 'Alice Smith',
    currency: new Currency('USD'),
    controls: new CardControls(
        perTransactionLimit: Money::from(10000, 'USD'),
        dailyLimit: Money::from(50000, 'USD'),
        allowedMccs: ['5732', '5812']
    )
));

// 2. Freeze Card
$frozenCard = $driver->freezeCard($card->id);

// 3. Request PCI-isolated Reveal Token for PAN/CVV display
$revealSession = $driver->createCardRevealSession($card->id);
echo $revealSession->ephemeralToken; // Token used by frontend iframe
```

---

### 2.8 Webhook Event Deduplication

Prevent duplicate business execution from third-party webhook retries using the framework-agnostic `WebhookDeduplicator`:

```php
use PayKit\Support\ArrayEventLockStore;
use PayKit\Support\WebhookDeduplicator;

$deduplicator = new WebhookDeduplicator(new ArrayEventLockStore()); // Or host PSR-16 cache

$result = $deduplicator->executeOnce(
    driverKey: 'stripe',
    eventId: 'evt_3M2e512eZvKYlo2C0',
    callback: function () use ($orderId) {
        // Business logic runs exactly once
        Order::fulfill($orderId);
        return true;
    },
    ttlSeconds: 86400 // Lock for 24 hours
);
```

---

## 3) Canonical `src/` Layout Sitemap

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
    ProvidesGatewayErrorLogContract.php
    ProvidesGatewayInfoContract.php
    EvaluatesGatewayVisibilityContract.php

    # --- core payments & webhooks ---
    PaymentGatewayPaymentsContract.php
    PaymentGatewayPaymentStatusMapperContract.php
    PaymentGatewayVerificationContract.php
    PaymentGatewayWebhooksContract.php

    # --- saved methods & tokenization ---
    PaymentGatewaySavedMethodsContract.php
    PaymentGatewayCardTokenizationContract.php

    # --- refunds & disputes ---
    PaymentGatewayRefundsContract.php
    PaymentGatewayDisputesContract.php

    # --- virtual accounts & watchers ---
    PaymentGatewayVirtualAccountsContract.php
    PaymentGatewayVirtualAccountWebhookWatcherContract.php
    PaymentGatewayVirtualAccountPollingWatcherContract.php
    PaymentGatewayVirtualAccountReconcileContract.php
    PaymentGatewayInboundTransferApprovalContract.php

    # --- payouts & bulk transfers ---
    PaymentGatewayPayoutsContract.php
    PaymentGatewayBulkPayoutsContract.php
    PaymentGatewayPayoutDestinationResolverContract.php
    PaymentGatewayPayoutMethodsContract.php
    PaymentGatewayBeneficiariesContract.php

    # --- balances, ledger & transfers ---
    PaymentGatewayBalancesContract.php
    PaymentGatewayLedgerContract.php
    PaymentGatewayTransfersContract.php

    # --- virtual cards issuing suite ---
    PaymentGatewayCardIssuingContract.php
    PaymentGatewayCardManagementContract.php
    PaymentGatewayCardControlsContract.php
    PaymentGatewayCardTransactionsContract.php
    PaymentGatewayCardAuthorizationWatcherContract.php
    PaymentGatewayCardSensitiveDetailsContract.php

    # --- reconciliation & diagnostics ---
    PaymentGatewayReconcileContract.php
    PaymentGatewayDiagnosticsContract.php
    PaymentGatewayScriptsContract.php
    PaymentGatewayUiContract.php
    PaymentGatewayFrontendConfigContract.php

  Payload/
    Common/
      # primitives & enums
      Money.php
      Amount.php
      Currency.php
      Country.php
      Reference.php
      ProviderRef.php
      Metadata.php
      IdempotencyKey.php
      GatewayFailure.php
      CanonicalPaymentStatus.php
      CanonicalPayoutStatus.php
      CanonicalRefundStatus.php
      VirtualAccountPurpose.php
      VirtualAccountUsage.php
      VirtualAccountStatus.php
      BeneficiaryStatus.php
      VirtualCardStatus.php
      AccountTransactionDirection.php
      AccountTransactionType.php
      PayoutSourceType.php
      InboundTransferDecision.php

      # compliance & identity
      CustomerIdentity.php
      IdentityIdentifier.php

      # payout destinations
      PayoutDestinationPayload.php
      PayoutDestination.php
      BankAccountDestination.php
      MobileMoneyDestination.php
      WalletDestination.php
      CardDestination.php
      CryptoDestination.php
      BeneficiaryDestination.php
      ProviderBalanceDestination.php
      PayoutSource.php
      BulkPayoutItem.php

      # balances, ledger & cards
      BalanceAmount.php
      BalanceAccount.php
      AccountTransaction.php
      VirtualCardRecord.php
      CardControls.php
      Beneficiary.php
      SavedMethod.php
      CardSummary.php
      CardBrand.php
      CardFingerprint.php

      # manifest & capabilities
      GatewayManifest.php
      GatewayFeatureSet.php
      GatewaySupportMatrix.php
      SupportedCurrency.php
      SupportedCountry.php
      GatewayRequirements.php

    Requests/
      PaymentInitiateRequest.php
      PaymentVerifyRequest.php
      PayoutRequest.php
      PayoutVerifyRequest.php
      BulkPayoutRequest.php
      BulkPayoutVerifyRequest.php
      PayoutDestinationResolveRequest.php
      VirtualAccountCreateRequest.php
      VirtualAccountGetRequest.php
      ListVirtualAccountsRequest.php
      InboundTransferDecisionRequest.php
      LedgerQuery.php
      TransferRequest.php
      VirtualCardCreateRequest.php
      VirtualCardGetRequest.php
      ListVirtualCardsRequest.php
      VirtualCardStatusUpdateRequest.php
      VirtualCardControlsRequest.php
      BeneficiaryCreateRequest.php
      BeneficiaryUpdateRequest.php
      RefundRequest.php
      RefundVerifyRequest.php
      DisputeQuery.php

    Responses/
      PaymentInitiateResult.php
      PaymentVerifyResult.php
      PayoutResult.php
      PayoutStatusResult.php
      BulkPayoutResult.php
      PayoutDestinationResolveResult.php
      VirtualAccountProvisionResult.php
      VirtualAccountRecord.php
      VirtualAccountList.php
      InboundTransferDecisionResult.php
      LedgerPage.php
      TransferResult.php
      CardRevealSessionResult.php
      BeneficiaryCreateResult.php
      BeneficiaryUpdateResult.php
      BeneficiaryList.php

  Support/
    WebhookDeduplicator.php
    EventLockStoreInterface.php
    ArrayEventLockStore.php
```

---

## 4) Driver Implementation Guide

To create a new PayKit driver (e.g. `PayKitStripeDriver`):

1. Extend `AbstractPaymentGatewayDriver` (or implement `PaymentGatewayDriverContract`).
2. Implement capability interfaces for features your provider supports:

```php
use PayKit\Contracts\PaymentGatewayCardIssuingContract;
use PayKit\Contracts\PaymentGatewayManifestProviderContract;
use PayKit\Contracts\PaymentGatewayPayDriverContract;
use PayKit\Contracts\PaymentGatewayPayoutsContract;
use PayKit\Drivers\AbstractPaymentGatewayDriver;

final class StripeDriver extends AbstractPaymentGatewayDriver implements
    PaymentGatewayPayDriverContract,
    PaymentGatewayPayoutsContract,
    PaymentGatewayCardIssuingContract,
    PaymentGatewayManifestProviderContract
{
    public function driverKey(): string
    {
        return 'stripe';
    }

    // Implement contract methods accepting DTOs and returning response DTOs
}
```

3. Register your driver with `Pay`:

```php
Pay::register('stripe', StripeDriver::class);
```

---

## 📄 License

The PayKit Gateway SDK is open-sourced software licensed under the [MIT license](LICENSE).
