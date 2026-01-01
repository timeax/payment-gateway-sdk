<?php declare(strict_types=1);

namespace PayKit\Contracts;

use PayKit\Payload\Common\GatewayConfig;
use PayKit\Payload\Common\GatewayRequirements;

interface PaymentGatewayRequirementsContract
{
    /** @param array<string,mixed> $context */
    public function requirements(array $context = [], ?GatewayConfig $config = null): GatewayRequirements;
}