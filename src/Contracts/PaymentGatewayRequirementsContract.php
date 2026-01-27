<?php declare(strict_types=1);

namespace PayKit\Contracts;

use Timeax\ConfigSchema\Support\ConfigBag;
use PayKit\Payload\Common\GatewayRequirements;

interface PaymentGatewayRequirementsContract
{
    /** @param array<string,mixed> $context */
    public function requirements(array $context = [], ?ConfigBag $config = null): GatewayRequirements;
}


