<?php declare(strict_types=1);

namespace PayKit\Contracts;

use PayKit\Payload\Common\GatewayConfig;
use PayKit\Payload\Common\HealthCheckResult;

interface PaymentGatewayDiagnosticsContract
{
    public function diagnostics(?GatewayConfig $config): HealthCheckResult;
}