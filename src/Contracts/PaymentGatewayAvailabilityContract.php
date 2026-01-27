<?php declare(strict_types=1);

namespace PayKit\Contracts;

use Timeax\ConfigSchema\Support\ConfigBag;

interface PaymentGatewayAvailabilityContract
{
    /** @param array<string,mixed> $context */
    public function isAvailable(array $context = [], ?ConfigBag $config = null): bool;
}


