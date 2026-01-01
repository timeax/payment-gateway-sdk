<?php declare(strict_types=1);

namespace PayKit\Contracts;

use PayKit\Payload\Common\GatewayConfig;

interface PaymentGatewayAvailabilityContract
{
    /** @param array<string,mixed> $context */
    public function isAvailable(array $context = [], ?GatewayConfig $config = null): bool;
}