<?php declare(strict_types=1);

namespace PayKit\Contracts;

use Throwable;

/**
 * Optional host/provider hook for logging/reporting gateway-related errors.
 *
 * PayKit can call this when it catches an exception while:
 * - resolving a gateway registration/provider
 * - filtering/listing gateways
 * - initiating/verifying payments (host-side orchestration errors, not provider responses)
 *
 * Implementations can forward to any logger (Laravel Log, Sentry, Bugsnag, etc.).
 */
interface ProvidesGatewayErrorLogContract
{
    /**
     * Report an exception in a gateway flow.
     *
     * @param string $stage A short stage key (e.g. "pay.via", "pay.list", "payment.initiate", "payment.verify")
     * @param Throwable $error The exception/error thrown.
     * @param array<string,mixed> $context Extra safe context (should already be redacted).
     */
    public function reportGatewayError(string $stage, Throwable $error, array $context = []): void;

    /**
     * Report a non-exception warning (soft failure / skip reason).
     *
     * @param string $stage A short stage key (e.g. "pay.list.filter", "provider.shouldShow")
     * @param string $message Human-readable warning message.
     * @param array<string,mixed> $context Extra safe context (should already be redacted).
     */
    public function reportGatewayWarning(string $stage, string $message, array $context = []): void;
}