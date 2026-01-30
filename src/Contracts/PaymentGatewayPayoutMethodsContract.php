<?php declare(strict_types=1);

namespace PayKit\Contracts;

use Timeax\ConfigSchema\Support\ConfigBag;
use PayKit\Payload\Common\PayoutMethodDescriptor;

interface PaymentGatewayPayoutMethodsContract
{
    /**
     * Return payout methods supported by this gateway (bank, momo, crypto, etc.).
     *
     * The returned methods should include enough metadata for the host to:
     * - present a method picker UI
     * - render an input schema OR mount a UI module (if required)
     * - inject scripts (if required)
     *
     * @param array<string,mixed> $context
     * @return array<int,PayoutMethodDescriptor>
     */
    public function listPayoutMethods(array $context = [], ?ConfigBag $config = null): array;

    /**
     * Fetch a single payout method by key.
     *
     * @param array<string,mixed> $context
     */
    public function getPayoutMethod(string $methodKey, array $context = [], ?ConfigBag $config = null): ?PayoutMethodDescriptor;
}


